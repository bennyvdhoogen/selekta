import React from 'react';
import ReactDOM from 'react-dom';
import { Router, Route } from 'react-router';
import { createHistory } from 'history';

var createBrowserHistory = require('history/lib/createBrowserHistory');

/*
 Import components
*/

/*
 Import components
*/

import Vault from './components/Vault';
//import App from './components/App';

/*
  Routes
*/

var routes = (
  <Router history={createHistory()}>
  <Route path="/" component={Vault} />
    <Route path="/vault" component={Vault} />

  </Router>
)

ReactDOM.render(routes, document.querySelector('#main'));
