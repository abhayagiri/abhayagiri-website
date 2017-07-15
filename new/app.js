//Babel
import 'babel-polyfill';

//React
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { Router, Route, IndexRedirect, browserHistory } from 'react-router';

//Pages
import Main from './components/ui/main/main.js';
import Talks from './components/pages/talks/talks.js';
import InfoPage from './components/widgets/infopage/infopage';
import Subpage from './components/widgets/subpage/subpage/subpage';

class App extends Component {
    render() {
        return (
            <Router history={browserHistory}>
                <Route path="/new" name="Home" component={Main}>
                    <IndexRedirect to="talks" />

                    <Route name="Talks" path="talks(/:page)" component={Talks}>
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
            </Router>
        );
    }
}

ReactDOM.render(<App />, document.querySelector('#root'));

export default App;