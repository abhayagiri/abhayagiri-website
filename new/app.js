//Babel
import 'babel-polyfill';

//React
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

//React Router
import { Router, Route, IndexRoute, IndexRedirect, Redirect, browserHistory } from 'react-router';
import applyMiddleware from 'react-router-apply-middleware'
import { useRelativeLinks, RelativeLink } from 'react-router-relative-links'

//i18n
import i18n from './i18n';
import { I18nextProvider } from 'react-i18next';

//Google Analytics
import ReactGA from 'react-ga';
ReactGA.initialize('UA-34323281-1');

//Pages
import Main from './components/ui/main/main';
import Page from './components/ui/page/page';
import Subpage from './components/ui/subpage/subpage/subpage';

import Residents from './components/content/residents/residents';

import TalksPage from './components/content/talks/talks';
import TalksByType from './components/content/talks/talks-pages/by-type';
import TalksByTeacher from './components/content/talks/talks-pages/by-teacher';
import TalksBySubject from './components/content/talks/talks-pages/by-subject';
import TalksByCollection from './components/content/talks/talks-pages/by-collection';
import TalksById from './components/content/talks/talks-pages/by-id';
import TalksByQuery from './components/content/talks/talks-pages/by-query';

import Teachers from './components/shared/categories/category-pages/teachers';
import Subjects from './components/shared/categories/category-pages/subjects';
import SubjectGroups from './components/shared/categories/category-pages/subject-groups';
import Collections from './components/shared/categories/category-pages/collections';
import CollectionGroups from './components/shared/categories/category-pages/collection-groups';

class App extends Component {
    logPageView() {
        ReactGA.set({ page: window.location.pathname + window.location.search });
        ReactGA.pageview(window.location.pathname + window.location.search);
    }

    localizedRoutes(path, lng) {
        return (
            <Route path={path} name="Home" component={Main} lng={lng}>
                <IndexRedirect to="talks" />

                {/* About */}
                <Route name="About" path="about" component={Page}>
                    <Route path=":subpage" component={Subpage} />
                    <IndexRedirect to="purpose" />
                </Route>

                {/* Community */}
                <Route name="Community" path="community" component={Page}>
                    <IndexRedirect to="residents" />
                    <Route name="Residents" path="residents" component={Residents} />
                    <Route path=":subpage" component={Subpage} />
                </Route>

                {/* Support */}

                <Route name="Support" path="support" component={Page}>
                    <IndexRedirect to="ethos" />
                    <Route path=":subpage" component={Subpage} />
                </Route>

                {/* Visiting */}
                <Route name="Visiting" path="visiting" component={Page}>
                    <IndexRedirect to="daily-schedule" />
                    <Route path=":subpage" component={Subpage} />
                </Route>

                {/* Talks */}
                <Route name="Talks" path="talks" component={TalksPage}>
                    <IndexRedirect to="types" />

                    <Route name="Search" path="search/:query" component={TalksByQuery} />

                    {/* Latest */}
                    <Route name="Latest" path="types/:typeId" component={TalksByType} />
                    <Redirect from="types" to="types/2-dhamma-talks" />

                    {/* Teachers */}
                    <Route name="Teachers" path="teachers" component={Teachers} />
                    <Route name="Teachers" path="teachers/:authorId" component={TalksByTeacher} />

                    {/* Subjects */}
                    <Route name="Subjects" path="subjects" component={SubjectGroups} />
                    <Route name="Subjects" path="subjects/:subjectGroupId" component={Subjects} />
                    <Route name="Subjects" path="subjects/:subjectGroupId/:subjectId" component={TalksBySubject} />

                    {/* Collections */}
                    <Route name="Collections" path="collections" component={CollectionGroups} />
                    <Route name="Collections" path="collections/:playlistGroupId" component={Collections} />
                    <Route name="Collections" path="collections/:playlistGroupId/:playlistId" component={TalksByCollection} />

                    {/* Older route redirects */}
                    <Redirect from="latest" to="types/2-dhamma-talks" />
                    <Redirect from="by-type" to="types" />
                    <Redirect from="by-type/:talkTypeId" to="types/:talkTypeId" />
                    <Redirect from="by-teacher" to="teachers" />
                    <Redirect from="by-teacher/:authorId" to="teachers/:authorId" />
                    <Redirect from="by-subject" to="subjects" />
                    <Redirect from="by-subject/:subjectGroupId" to="subjects/:subjectGroupId" />
                    <Redirect from="by-subject/:subjectGroupId/:subjectId" to="subjects/:subjectGroupId/:subjectId" />
                    <Redirect from="by-collection" to="collections" />
                    <Redirect from="by-collection/:playlistId" to="collections" />
                    <Redirect from="by-collection/:playlistGroupId/:playlistId" to="collections/:playlistGroupId/:playlistId" />

                    <Route name="Talk" path=":talkId" component={TalksById} />
                </Route>
            </Route>
        );
    }

    render() {
        return (
            <I18nextProvider i18n={i18n}>
                <Router
                    history={browserHistory}
                    onUpdate={this.logPageView}
                    render={applyMiddleware(useRelativeLinks())}>
                    {this.localizedRoutes('/new', 'en')}
                    {this.localizedRoutes('/new/th', 'th')}
                </Router>
            </I18nextProvider>
        );
    }
}

ReactDOM.render(<App />, document.querySelector('#root'));

export default App;
