//Babel
import 'babel-polyfill';

//React
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { I18nextProvider } from 'react-i18next';
import { Router, Route, IndexRoute, IndexRedirect, Redirect, browserHistory } from 'react-router';
import ReactGA from 'react-ga';

import i18n from './i18n';

//Pages
import Main from './components/ui/main/main';
import TalksContainer from './components/pages/talks/container';
import LatestTalks from './components/pages/talks/latest-talks';
import AuthorIndex from './components/pages/talks/author-index';
import AuthorTalks from './components/pages/talks/author-talks';
import SubjectGroupIndex from './components/pages/talks/subject-group-index';
import SubjectIndex from './components/pages/talks/subject-index';
import SubjectTalks from './components/pages/talks/subject-talks';
import PlaylistIndex from './components/pages/talks/playlist-index';
import PlaylistTalks from './components/pages/talks/playlist-talks';
import TalkTypeIndex from './components/pages/talks/talk-type-index';
import TalkTypeTalks from './components/pages/talks/talk-type-talks';
import TalkPage from './components/pages/talks/talk-page';
import InfoPage from './components/widgets/infopage/infopage';
import Subpage from './components/widgets/subpage/subpage/subpage';

ReactGA.initialize('UA-34323281-1');

function logPageView() {
    ReactGA.set({ page: window.location.pathname + window.location.search });
    ReactGA.pageview(window.location.pathname + window.location.search);
}

class App extends Component {

    localizedRoutes(path, lng) {
        return (
            <Route path={path} name="Home" component={Main} lng={lng}>

                <IndexRedirect to="talks" />

                <Route path="talks" component={TalksContainer}>
                    <Route path="latest" component={LatestTalks} />
                    <Route path="by-teacher" component={AuthorIndex} />
                    <Route path="by-teacher/:authorId" component={AuthorTalks} />
                    <Route path="by-subject" component={SubjectGroupIndex} />
                    <Route path="by-subject/:subjectGroupId" component={SubjectIndex} />
                    <Route path="by-subject/:subjectGroupId/:subjectId" component={SubjectTalks} />
                    <Route path="by-collection" component={PlaylistIndex} />
                    <Route path="by-collection/:playlistId" component={PlaylistTalks} />
                    <Route path="by-type" component={TalkTypeIndex} />
                    <Route path="by-type/:talkTypeId" component={TalkTypeTalks} />
                    <Redirect from="type" to="by-type" />
                    <Redirect from="type/:talkTypeId" to="by-type/:talkTypeId" />
                    <Route path=":talkId" component={TalkPage} />
                    <IndexRoute component={LatestTalks} />
                </Route>

                <Route name="About" path="about" component={InfoPage}>
                    <Route name="Purpose" path="purpose" component={Subpage} />
                </Route>

                <Route name="Community" path="community" component={InfoPage}>
                    {/*<Route name="Residents" path="residents" component={Residents}/>*/}
                    <Route name="Pacific Hermitage" path="pacific-hermitage" component={Subpage} />
                    <Route name="Associated Monasteries" path="associated-monasteries" component={Subpage} />
                    <Route name="Monastic Training for Women" path="monastic-training-for-women" component={Subpage} />
                    <Route name="Associated Lay Groups" path="associated-lay-groups" component={Subpage} />
                    <Route name="Upasika Program" path="upasika-program" component={Subpage} />
                    <Route name="Subscribe" path="subscribe" component={Subpage} />
                </Route>
            </Route>
        );
    }

    render() {
        return (
            <I18nextProvider i18n={i18n}>
                <Router history={browserHistory} onUpdate={logPageView}>
                    {this.localizedRoutes('/new', 'en')}
                    {this.localizedRoutes('/new/th', 'th')}
                </Router>
            </I18nextProvider>
        );
    }
}

ReactDOM.render(<App />, document.querySelector('#root'));

export default App;
