"use strict";

window.router = {

  admin: {
    'home': '/',
    'login': '/login'
  }
};

window.host = {
  'admin': 'http://admin.local.xgc:8000',
  'api': 'http://api.local.xgc:8000',
  'web': 'http://local.xgc:8000'
};

window.router.get = function(host, name) {
  return window.host[host] + window.router[host][name];
};
