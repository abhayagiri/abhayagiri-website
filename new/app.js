//Babel
import 'babel-polyfill';

//React
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { Router, Route, IndexRedirect, browserHistory } from 'react-router';

//Pages
import Main from './components/ui/main/main.js';
import Talks from './components/pages/talks/talks.js';

class App extends Component {
    render() {
        return (
            <Router history={browserHistory}>
                <Route path="/new" component={Main}>
                    <IndexRedirect to="talks" />
                    <Route name="Talks" path="talks" component={Talks}>
                    </Route>
                </Route>
            </Router>
        );
    }
}

ReactDOM.render(<App />, document.querySelector('#root'));

export default App;