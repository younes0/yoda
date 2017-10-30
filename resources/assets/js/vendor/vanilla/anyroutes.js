/*

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0
 
 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
*/

var anyroutes = function() {

  var _routes = [];
  var _beforeCallback;
  var _defaultCallback;

  this.parseRoute = function(path) {
    this.parseGroups = function(loc) {
      var nameRegexp = new RegExp(":([^/.\\\\]+)", "g"); 
      var newRegexp = "" + loc;
      var groups = {};
      var matches = null;
      var i = 0;

      // Find the places to edit.
      while(matches = nameRegexp.exec(loc)) {
        groups[matches[1]] = i++;
        newRegexp = newRegexp.replace(matches[0], "([^/.\\\\]+)"); 
      }

      newRegexp += "$"; // Only do a full string match

      return { "groups" : groups, "regexp": new RegExp(newRegexp)};
    };
      
    return this.parseGroups(path); 
  };

  var matchRoute = function(url, e, before) {

    if (before && _beforeCallback) {
      _beforeCallback({"url": url, "params": params, "values" : values, "e": e});
    }
  
    var route = null;
    for(var i = 0; route = _routes[i]; i ++) {

      var routeMatch = route.regex.regexp.exec(url);
      if (!routeMatch !== false) continue;
      
      var params = {};
      for(var g in route.regex.groups) {
        var group = route.regex.groups[g];
        params[g] = routeMatch[group + 1];
      }

      var values = {};

      route.callback({"url": url, "params": params, "values" : values, "e": e});
      return true;
    }

    if (_defaultCallback) {
      _defaultCallback({"url": url, "params": params, "values" : values, "e": e});
    }

    return false;
  };

  this.before = function(callback) {
    _beforeCallback = callback;
    return this;
  };

  this.defaultCallback = function(callback) {
    _defaultCallback = callback;
    return this;
  };

  this.any = function(route, callback) {
    _routes.push({regex: this.parseRoute(route), "callback": callback});
    return this;
  };

  this.test = function(url) {
    matchRoute(url);
  };

  this.getRoutes = function() {
    return _routes;
  };

  this.loadCurrent = function(before) {
    before = typeof before !== 'undefined' ? before : true;
    matchRoute(document.location.pathname, null, before);
  };

  var attach = function() {

    if (window.addEventListener) {
      window.addEventListener("load", function(e) {
        matchRoute(document.location.pathname, null, true);
      }, false);

    } else {
      window.attachEvent("onload", function(e) {
        matchRoute(document.location.pathname, null, true);
      });
    }
    
  }();

};
