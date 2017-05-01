'use strict';
/**
 * Overrides
 */

String.prototype.replaceAll = function(search, replacement) {
    let target = this;
    return target.split(search).join(replacement);
};

HTMLDocument.prototype._id = HTMLElement.prototype._id = function(id) {
    return this.getElementById(id);
};

HTMLDocument.prototype._class = HTMLElement.prototype._class = function(clazz) {
    return this.getElementsByClassName(clazz);
};

HTMLDocument.prototype._tag = HTMLElement.prototype._tag = function(tag) {
    return this.getElementsByTagName(tag);
};

HTMLElement.prototype._put = function(key, value, sep) {

    this.innerHTML = this.innerHTML.replaceAll((sep || "{") + key + (sep || "}"), value);
};

HTMLFormElement.prototype._submit = function(event, onSuccess, onFailure, preSubmit, postSubmit) {
    event.preventDefault();

    preSubmit = (preSubmit || function() {}).bind(this);
    postSubmit = (postSubmit || function() {}).bind(this);
    onSuccess = (onSuccess || function() {}).bind(this);
    onFailure = (onFailure || function() {}).bind(this);

    let inputs = this._tag('input').concat(this._tag('select'));

    let params = [];

    if (this.method === "GET") {

        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].type !== 'file') {
                params[inputs[i].name] = inputs[i].value;
            }
        }
    } else {
        params = new FormData(this);
    }

    Request[this.method.toLowerCase()](this.action, params, onSuccess, onFailure, preSubmit, postSubmit);
};

HTMLButtonElement.prototype._submit = HTMLInputElement.prototype._submit = function(event, onSuccess, onFailure, preSubmit, postSubmit) {
    if (this.form) {
        this.form._submit(event, onSuccess, onFailure, preSubmit, postSubmit);
    } else {
        console.error("This element is not in a form");
    }
};

HTMLCollection.prototype.forEach = function(cb) {
    for (let i = 0; i < this.length; i++) {
        cb(this[i]);
    }

    return this;
};

HTMLCollection.prototype.concat = function(other) {

    let ret = [];

    for (let i = 0; i < this.length; i++) {
        ret.push(this[i]);
    }

    for (let i = 0; i < other.length; i++) {
        ret.push(other[i]);
    }

    return this;
};

function _id(id) {
    return document._id(id);
}

function _class(clazz) {
    return document._class(clazz);
}

function _tag(tag) {
    return document._tag(tag);
}

/**
 * Request
 */

let Request = {};

Request.get = function (path, params, onSuccess, onFailure, preRequest, postRequest) {
    params = params || {};

    preRequest = (preRequest || function() {return true;}).bind(this);
    postRequest = (postRequest || function() {}).bind(this);
    onSuccess = (onSuccess || function() {console.info('success');}).bind(this);
    onFailure = (onFailure || function() {console.error('failure');}).bind(this);

    if(preRequest() === false) {
        return false;
    }

    let first = 0;
    let _params = "";
    for (let param in params) {
        if (!first) {
            _params += "&";
        }

        _params += param + "=" + params[param];
    }

    let http = new XMLHttpRequest();
    http.withCredentials = true;
    http.open("GET", path + "?" + _params, true);

    if(preRequest(http) === false) {
        return false;
    }

    http.onreadystatechange = function() {
        if (http.readyState === 4 && http.status === 200) {
            console.log(JSON.parse(http.response));
            onSuccess(http);
            postRequest(http);
        } else if (http.readyState === 4) {
            onFailure(http);
            postRequest(http);
        }
    };
    http.send();
};

Request.delete = function (path, params, onSuccess, onFailure, preRequest, postRequest) {
    params = params || {};

    preRequest = (preRequest || function() {return true;}).bind(this);
    postRequest = (postRequest || function() {}).bind(this);
    onSuccess = (onSuccess || function() {console.info('success');}).bind(this);
    onFailure = (onFailure || function() {console.error('failure');}).bind(this);

    if(preRequest() === false) {
        return false;
    }

    params._method = params._method || "DELETE";

    let first = 0;
    let _params = "";
    for (let param in params) {
        if (!first) {
            _params += "&";
        }

        _params += param + "=" + params[param];
    }

    let http = new XMLHttpRequest();
    http.withCredentials = true;
    http.open("DELETE", path + "?" + _params, true);

    if(preRequest(http) === false) {
        return false;
    }

    http.onreadystatechange = function() {
        if (http.readyState === 4 && http.status === 200) {
            console.log(JSON.parse(http.response));
            onSuccess(http);
            postRequest(http);
        } else if (http.readyState === 4) {
            onFailure(http);
            postRequest(http);
        }
    };
    http.send();
};

Request.post = function (path, params, onSuccess, onFailure, preRequest, postRequest) {
    params = params || {};

    preRequest = (preRequest || function() {return true;}).bind(this);
    postRequest = (postRequest || function() {}).bind(this);
    onSuccess = (onSuccess || function() {console.info('success');}).bind(this);
    onFailure = (onFailure || function() {console.error('failure');}).bind(this);

    if(preRequest() === false) {
        return false;
    }

    var http = new XMLHttpRequest();
    http.withCredentials = true;

    http.open("POST", path, true);
    //http.setRequestHeader("Content-Type", "multipart/form-data; charset=utf-8; boundary=false");

    if(preRequest(http) === false) {
        return false;
    }

    http.onreadystatechange = function() {
        if (http.readyState === 4 && http.status === 200) {
            console.log(JSON.parse(http.response));
            onSuccess(http);
            postRequest(http);
        } else if (http.readyState === 4) {
            onFailure(http);
            postRequest(http);
        }
    };
    http.send(params);
};

Request.put = function (path, params, onSuccess, onFailure, preRequest, postRequest) {
    params = params || new FormData();
    params.append("_method", "PUT");

    preRequest = (preRequest || function() {return true;}).bind(this);
    postRequest = (postRequest || function() {}).bind(this);
    onSuccess = (onSuccess || function() {console.info('success');}).bind(this);
    onFailure = (onFailure || function() {console.error('failure');}).bind(this);

    if(preRequest() === false) {
        return false;
    }

    let http = new XMLHttpRequest();
    http.withCredentials = true;

    http.open("POST", path, true);
    //http.setRequestHeader("Content-Type", "multipart/form-data; charset=utf-8; boundary=false");

    if(preRequest(http) === false) {
        return false;
    }

    http.onreadystatechange = function() {
        if (http.readyState === 4 && http.status === 200) {
            console.log(JSON.parse(http.response));
            onSuccess(http);
            postRequest(http);
        } else if (http.readyState === 4) {
            onFailure(http);
            postRequest(http);
        }
    };
    http.send(params);
};


(function() {

    let map;

    Location.prototype.getQueryParam = function(key, def) {
        map = map || this.getQueryParams();
        return map[key] || def;
    };

    Location.prototype.getQueryParams = function() {
        let result = null;
        let tmp = [];
        map = [];

        location.search.substr(1)
        .split("&")
        .forEach(function (item, key) {
                tmp = item.split("=");

                if (tmp[0].endsWith("[]")) {
                    key = tmp[0].slice(0, -2);
                    map[key] = map[key] || [];
                    map[key].push(tmp[1]);
                } else {
                    map[tmp[0]] = tmp[1];
                }
            }
        );

        return map;
    };

    Location.prototype.hasQueryParam = function(key) {
        map = map || this.getQueryParams();
        return map[key] === undefined;
    };

    Location.prototype.putQueryParam = function(key, value) {
        map = map || this.getQueryParams();
        map[key] = value;
        return this;
    };

    Location.prototype.removeQueryParam = function(key, index) {
        map = map || this.getQueryParams();
        if (Array.isArray(key)) {
            if (index === undefined) {
                delete map[key];
            } else {
                delete map[key][i];
            }
        } else {
            delete map[key];
        }
        return this;
    };

    Location.prototype.generateQuery = function() {
        map = map || this.getQueryParams();
        let str = "";
        let first = true;

        for (let i in map) {
            if (!first) {
                str += "&";
            }
            first = false;
            if (Array.isArray(map[i])) {
                for (let j in map) {
                    str += i + "[]=" + map[j];
                }
            } else {
                str += i + "=" + map[i];
            }
        }

        if (str === "") {
            return str;
        }

        return "?" + str;
    };
})();
