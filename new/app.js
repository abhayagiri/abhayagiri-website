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
import InfoPage from './components/widgets/infopage/infopage';
import Subpage from './components/widgets/subpage/subpage/subpage';

import TalksPage from './components/pages/talks/talks';
import TalksByType from './components/pages/talks/talks-pages/by-type';
import TalksByTeacher from './components/pages/talks/talks-pages/by-teacher';
import TalksBySubject from './components/pages/talks/talks-pages/by-subject';

import Teachers from './components/pages/categories/category-pages/teachers';
import Subjects from './components/pages/categories/category-pages/subjects';

class App extends Component {
    logPageView() {
        ReactGA.set({ page: window.location.pathname + window.location.search });
        ReactGA.pageview(window.location.pathname + window.location.search);
    }

    localizedRoutes(path, lng) {
        return (
            <Route path={path} name="Home" component={Main} lng={lng}>

                <IndexRedirect to="talks" />
                {/* <Route path="talk/:talkId" component={TalkPage} /> */}
                
                <Redirect from="talks" to="talks/types" />

                <Route path="talks" component={TalksPage}>

                    {/* Categories */}
                    <Route path="teachers" component={Teachers} />
                    <Route path="subjects" component={Subjects} />

                    {/* Talks */}
                    <Route name="types" path="types/:typeId" component={TalksByType} />
                    <Redirect from="types" to="types/all" />
                    <Route path="teachers/:authorId" component={TalksByTeacher} />
                    <Route path="subjects/:subjectGroupId(/:subjectId)" component={TalksBySubject} />



                    {/* <Route path="collections" component={CategoryCollections} /> */}
                    {/* <Route path="subjects" component={CategorySubjects} />
                        <Route path="types" component={CategoryTypes} /> */}
                    {/* <Route path="by-teacher/:authorId" component={AuthorTalks} /> */}
                    {/* 
                    
                    <Route path="by-subject" component={SubjectGroupIndex} />
                    {/* <Route path="by-subject/:subjectGroupId" component={SubjectIndex} /> */}
                    {/* <Route path="by-subject/:subjectGroupId/:subjectId" component={SubjectTalks} />
                    <Route path="by-collection" component={PlaylistIndex} />
                    <Route path="by-collection/:playlistId" component={PlaylistTalks} />
                    <Route path="by-type" component={TalkTypeIndex} />
                    <Route path="by-type/:talkTypeId" component={TalkTypeTalks} /> */} */}
                    {/* <Redirect from="type" to="by-type" />
                    <Redirect from="type/:talkTypeId" to="by-type/:talkTypeId" />
                     */}
                    
                    
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
