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

import { GlobalsApp } from 'components/shared/globals/globals';

//Pages
import Main from './components/ui/main/main';
import Page from './components/ui/page/page';

import Residents from './components/content/residents/residents';

import TalksPage from './components/content/talks/talks';
import TalksLatest from './components/content/talks/talks-pages/latest';
import TalksByType from './components/content/talks/talks-pages/by-type';
import TalksByTeacher from './components/content/talks/talks-pages/by-teacher';
import TalksBySubject from './components/content/talks/talks-pages/by-subject';
import TalksByCollection from './components/content/talks/talks-pages/by-collection';
import TalksByCollectionGroup from './components/content/talks/talks-pages/by-collection-group';
import TalksById from './components/content/talks/talks-pages/by-id';
import TalksByQuery from './components/content/talks/talks-pages/by-query';

import Teachers from './components/shared/categories/category-pages/teachers';
import Subjects from './components/shared/categories/category-pages/subjects';
import SubjectGroups from './components/shared/categories/category-pages/subject-groups';
import Collections from './components/shared/categories/category-pages/collections';
import CollectionGroups from './components/shared/categories/category-pages/collection-groups';

import AlbumList from './components/content/gallery/album-list/album-list';
import Album from './components/content/gallery/album/album';

class App extends Component {
    logPageView() {
        ReactGA.set({ page: window.location.pathname + window.location.search });
        ReactGA.pageview(window.location.pathname + window.location.search);
    }

    localizedRoutes(path, lng) {
        return (
            <Route path={path} component={Main} lng={lng}>
                <IndexRedirect to="talks" />

                {/* Gallery */}
                <Route path="gallery">
                    <IndexRoute component={AlbumList}/>
                    <Route path=":albumId" component={Album} />
                </Route>

                {/* About */}
                <Route path="about">
                    <IndexRoute component={Page} />
                    <Route path="*" component={Page} />
                </Route>

                {/* Community */}
                <Route path="community">
                    <IndexRoute component={Page} />
                    <Route path="*" component={Page} />
                </Route>

                {/* Support */}
                <Route path="support">
                    <IndexRoute component={Page} />
                    <Route path="*" component={Page} />
                </Route>

                {/* Visiting */}
                <Route path="visiting">
                    <IndexRoute component={Page} />
                    <Route path="*" component={Page} />
                </Route>

                {/* Talks */}
                <Route path="talks" component={TalksPage}>
                    <IndexRoute component={TalksLatest} />

                    <Route path="latest" component={TalksLatest} />

                    <Route path="search/:query" component={TalksByQuery} />

                    {/* Latest */}
                    <Route path="types/:typeId" component={TalksByType} />
                    <Redirect from="types" to="types/2-dhamma-talks" />

                    {/* Teachers */}
                    <Route path="teachers" component={Teachers} />
                    <Route path="teachers/:authorId" component={TalksByTeacher} />

                    {/* Subjects */}
                    <Route path="subjects" component={SubjectGroups} />
                    <Route path="subjects/:subjectGroupId" component={Subjects} />
                    <Route path="subjects/:subjectGroupId/:subjectId" component={TalksBySubject} />

                    {/* Collections */}
                    <Route path="collections" component={CollectionGroups} />
                    <Route path="collections/:playlistGroupId" component={Collections} />
                    <Route path="collections/:playlistGroupId/latest" component={TalksByCollectionGroup} />
                    <Route path="collections/:playlistGroupId/:playlistId" component={TalksByCollection} />

                    {/* Older route redirects */}
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

                    <Route path=":talkId" component={TalksById} />
                </Route>
            </Route>
        );
    }

    render() {
        return (
            <GlobalsApp>
            <I18nextProvider i18n={i18n}>
                <Router
                    history={browserHistory}
                    onUpdate={this.logPageView}
                    render={applyMiddleware(useRelativeLinks())}>
                    {this.localizedRoutes('/new', 'en')}
                    {this.localizedRoutes('/new/th', 'th')}
                    <Route path="/" component={Main} lng="en">
                        <Route path="gallery">
                            <IndexRoute component={AlbumList}/>
                            <Route path=":albumId" component={Album} />
                        </Route>
                    </Route>
                    <Route path="/th" component={Main} lng="th">
                        <Route path="gallery">
                            <IndexRoute component={AlbumList}/>
                            <Route path=":albumId" component={Album} />
                        </Route>
                    </Route>
                </Router>
            </I18nextProvider>
            </GlobalsApp>
        );
    }
}

ReactDOM.render(<App />, document.querySelector('#root'));

export default App;
