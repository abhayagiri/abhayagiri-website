<?php

// TODO To be removed...

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class TalkSubjectRelation extends Relation
{
    /**
     * Create a new relation instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     *
     * @return void
     */
    public function __construct(Model $parent)
    {
        $query = (new Subject)->newQuery();
        parent::__construct($query, $parent);
    }

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints()
    {
        $this->query
            ->distinct()
            ->select('subjects.*')
            ->addSelect('tag_talk.talk_id')
            ->join('subject_tag', 'subject_tag.subject_id', '=', 'subjects.id')
            ->join('tag_talk', 'tag_talk.tag_id', '=', 'subject_tag.tag_id');
        if ($this->parent->id) {
            $this->query->where('tag_talk.talk_id', '=', $this->parent->id);
        }
    }

    /**
     * Set the constraints for an eager load of the relation.
     *
     * @param  array  $models
     *
     * @return void
     */
    public function addEagerConstraints(array $models)
    {
        $this->query
            ->whereIn('tag_talk.talk_id', $this->getKeys($models));
    }

    /**
     * Initialize the relation on a set of models.
     *
     * @param  array   $models
     * @param  string  $relation
     *
     * @return array
     */
    public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation($relation, $this->related->newCollection());
        }
        return $models;
    }

    /**
     * Match the eagerly loaded results to their parents.
     *
     * Based on Laravel's BelongToMany match method.
     *
     * @param  array   $models
     * @param  \Illuminate\Database\Eloquent\Collection  $results
     * @param  string  $relation
     *
     * @return array
     */
    public function match(array $models, Collection $results, $relation)
    {
        $dictionary = [];
        foreach ($results as $result) {
            $dictionary[$result->talk_id][] = $result;
        }
        foreach ($models as $model) {
            if (isset($dictionary[$key = $model->getKey()])) {
                $collection = $this->related->newCollection($dictionary[$key]);
                $model->setRelation($relation, $collection);
            }
        }

        return $models;
    }

    /**
     * Get the results of the relationship.
     *
     * @return mixed
     */
    public function getResults()
    {
        return $this->get();
    }

    /**
     * Execute the query as a "select" statement.
     *
     * Based on Laravel's BelongToMany get() method.
     *
     * @param  array  $columns
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($columns = ['*'])
    {
        // First we'll add the proper select columns onto the query so it is run with
        // the proper columns. Then, we will get the results and hydrate out pivot
        // models with the result of those columns as a separate model relation.
        $columns = $this->query->getQuery()->columns ? [] : $columns;

        if ($columns == ['*']) {
            $columns = [$this->related->getTable().'.*'];
        }

        $builder = $this->query->applyScopes();

        $models = $builder->addSelect($columns)->getModels();

        // If we actually found models we will also eager load any relationships that
        // have been specified as needing to be eager loaded. This will solve the
        // n + 1 query problem for the developer and also increase performance.
        if (count($models) > 0) {
            $models = $builder->eagerLoadRelations($models);
        }

        return $this->related->newCollection($models);
    }
}
