/*
  TopNavigation

*/
import React from 'react';  

var TopNavigation = React.createClass({
  componentWillMount(){
    //
  }, 
  render : function(){ 
    return ( 
        <div class="topnav col-xs-12">  
          <a href="/vault">vault </a> | <a href="/selections/">selections </a> | <a href="/config/">config </a> 
        </div>
      )  
  } 
});

export default TopNavigation; 