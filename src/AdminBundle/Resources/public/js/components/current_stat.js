"use strict";
/*
(function() {
  let stats = _id('admin-current-stats');
  let statsRaw = stats.innerHTML;
  let topic;

  stats.put('number', 0);

  mainSocket.connect('ws://127.0.0.1:8080').then((socket) => {

    //mainSocket.loadTopic('server/stats').publish("Hello world");
    mainSocket.loadTopic('server/stats').subscribe((uri, payload) => {
      console.log("message", uri, payload, payload.stats.users);
      stats.put('number', payload.stats.users);
    });
  });

})();
*/

