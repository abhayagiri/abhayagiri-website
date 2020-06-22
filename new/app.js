//Babel
import "core-js/stable";
import "regenerator-runtime/runtime";

//React
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

//React Router
import { Router, Route, IndexRoute, browserHistory } from 'react-router';
import applyMiddleware from 'react-router-apply-middleware'
import { useRelativeLinks, RelativeLink } from 'react-router-relative-links'

//i18n
import i18n from './i18n';
import { I18nextProvider } from 'react-i18next';

import { NotFound } from 'services/error.service';

import TalksPage from './components/content/talks/talks';
import TalksLatest from './components/content/talks/talks-pages/latest';
import TalksByTeacher from './components/content/talks/talks-pages/by-teacher';
import TalksBySubject from './components/content/talks/talks-pages/by-subject';
import TalksByCollection from './components/content/talks/talks-pages/by-collection';
import TalksByCollectionGroup from './components/content/talks/talks-pages/by-collection-group';
import TalksById from './components/content/talks/talks-pages/by-id';
import Teachers from './components/content/talks/talks-pages/teachers';
import Subjects from './components/content/talks/talks-pages/subjects';
import SubjectGroups from './components/content/talks/talks-pages/subject-groups';
import Collections from './components/content/talks/talks-pages/collections';
import CollectionGroups from './components/content/talks/talks-pages/collection-groups';

import AlbumList from './components/content/gallery/album-list/album-list';
import Album from './components/content/gallery/album/album';

// For Browser testing
import axios from 'axios';
axios.interceptors.request.use(function (config) {
    window._pendingXhrRequests++;
    return config;
}, function (error) {
    return Promise.reject(error);
});

axios.interceptors.response.use(function (response) {
    window._pendingXhrRequests--;
    return response;
}, function (error) {
    return Promise.reject(error);
});

class LegacyRedirect extends Component {

    render() {
        window.location.href = window.location.href;
        return <div>Redirecting to {window.location.href}</div>;
    }

}

class Main extends Component {

    componentDidMount() {
        this.getData(this.props);
    }

    componentWillReceiveProps(nextProps) {
        this.getData(nextProps);
    }

    getData(props) {
        this.getLanguage(props);
    }

    getLanguage(props) {
        const { lng } = props.route;
        if (lng !== i18n.lng) {
            i18n.changeLanguage(lng);
        }
    }

    render() {
        return (
            <div>{this.props.children}</div>
        );
    }
}

class App extends Component {

    localizeRoutes(root, lng) {
        return (
            <Route path={root} component={Main} lng={lng}>

                <IndexRoute component={LegacyRedirect} />

                <Route path="gallery">
                    <IndexRoute component={AlbumList} />
                    <Route path=":albumId" component={Album} />
                </Route>

                <Route path="talks" component={TalksPage}>

                    <IndexRoute component={TalksLatest} />

                    <Route path="latest" component={TalksLatest} />

                    <Route path="teachers" component={Teachers} />
                    <Route path="teachers/:authorId" component={TalksByTeacher} />

                    <Route path="subjects" component={SubjectGroups} />
                    <Route path="subjects/:subjectGroupId" component={Subjects} />
                    <Route path="subjects/:subjectGroupId/:subjectId" component={TalksBySubject} />

                    <Route path="collections" component={CollectionGroups} />
                    <Route path="collections/:playlistGroupId" component={Collections} />
                    <Route path="collections/:playlistGroupId/latest" component={TalksByCollectionGroup} />
                    <Route path="collections/:playlistGroupId/:playlistId" component={TalksByCollection} />

                    <Route path=":talkId" component={TalksById} />

                </Route> {/* talks */}

            </Route>
        );
    }

    render() {
        return (
            <I18nextProvider i18n={i18n}>
                <Router
                    history={browserHistory}
                    render={applyMiddleware(useRelativeLinks())}>
                    {this.localizeRoutes('/', 'en')}
                    {this.localizeRoutes('/th', 'th')}
                    <Route path="*" component={NotFound}/>
                </Router>
            </I18nextProvider>
        );
    }
}

ReactDOM.render(<App />, document.querySelector('#main'));

export default App;
