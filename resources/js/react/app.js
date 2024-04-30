import React from "react";
import ReactDOM from 'react-dom';
import i18n from 'i18next';
import { I18nextProvider } from 'react-i18next';
import {
    BrowserRouter as Router,
    Switch,
    Route
} from "react-router-dom";

import CategoryCollectionGroups from './talks/category-collection-groups';
import CategoryCollections from './talks/category-collections';
import CategorySubjectGroups from './talks/category-subject-groups';
import CategorySubjects from './talks/category-subjects';
import CategoryTeachers from './talks/category-teachers';
import LatestTalks from './talks/latest-talks';
import TalksByCollectionGroup from './talks/talks-by-collection-group';
import TalksByCollection from './talks/talks-by-collection';
import TalksById from './talks/talks-by-id';
import TalksBySubject from './talks/talks-by-subject';
import TalksByTeacher from './talks/talks-by-teacher';
import languageBundle from '../../../lang/index';

i18n.init({
    fallbackNS: 'common',
    resources: languageBundle,
    fallbackLng: 'en',
    debug: false
});

// Seed languages
i18n.changeLanguage('th');
i18n.changeLanguage('en');

function LocalizedAppRoutes(props) {
    const { lng } = props;
    const p = lng === 'th' ? '/th' : '';
    return (
        <Switch>
            <Route path={`${p}/talks/collections/:playlistGroupId/latest`}>
                <TalksByCollectionGroup lng={lng} />
            </Route>
            <Route path={`${p}/talks/collections/:playlistGroupId/:playlistId`}>
                <TalksByCollection lng={lng} />
            </Route>
            <Route path={`${p}/talks/collections/:playlistGroupId`}>
                <CategoryCollections lng={lng} />
            </Route>
            <Route path={`${p}/talks/collections`}>
                <CategoryCollectionGroups lng={lng} />
            </Route>
            <Route path={`${p}/talks/subjects/:subjectGroupId/:subjectId`}>
                <TalksBySubject lng={lng} />
            </Route>
            <Route path={`${p}/talks/subjects/:subjectGroupId`}>
                <CategorySubjects lng={lng} />
            </Route>
            <Route path={`${p}/talks/subjects`}>
                <CategorySubjectGroups lng={lng} />
            </Route>
            <Route path={`${p}/talks/teachers/:authorId`}>
                <TalksByTeacher lng={lng} />
            </Route>
            <Route path={`${p}/talks/teachers`}>
                <CategoryTeachers lng={lng} />
            </Route>
            <Route path={`${p}/talks/:talkId`}>
                <TalksById lng={lng} />
            </Route>
            <Route path={`${p}/talks`}>
                <LatestTalks lng={lng} />
            </Route>
        </Switch>
    );
}

function App(props) {
    return (
        <I18nextProvider i18n={i18n}>
            <Router>
                <Route>
                    <LocalizedAppRoutes lng="th" />
                    <LocalizedAppRoutes lng="en" />
                </Route>
            </Router>
        </I18nextProvider>
    );
}

ReactDOM.render(<App />, document.querySelector('#main'));
