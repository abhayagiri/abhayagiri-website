//Babel
import 'babel-polyfill';

//React
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { Router, Route, IndexRedirect, browserHistory } from 'react-router';

//Pages
import Main from './components/ui/main/main.js';

class App extends Component {
    render() {
        return (
            <Router history={browserHistory}>
                <Route path="/" component={Main}>
                </Route>
            </Router>
        );
    }
}

ReactDOM.render(<App />, document.querySelector('#root'));

export default App;