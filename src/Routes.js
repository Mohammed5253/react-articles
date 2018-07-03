
import React from "react";
import {  BrowserRouter as Router,
  Route,
  Link,
  Redirect,
  withRouter, Switch } from "react-router-dom";

  import IndexComponent from "./container/IndexComponent/IndexComponent";
import PostForm from "./container/PostForm/PostForm";



export default () =>
  <Switch>
    <Route path="/" exact component={IndexComponent} />
    <Route path="/posts" exact component={PostForm} />
  </Switch>;
