var __wpo = {
  "assets": {
    "main": [
      "/js/app.js",
      "/css/app.css",
      "./mix-manifest.json"
    ],
    "additional": [],
    "optional": []
  },
  "externals": [],
  "hashesMap": {
    "76d83f9031f9065674ee37094f4a6181e77e7ca8": "/js/app.js",
    "4719be444265bc4cb3ceddfdacf7f194b642b650": "/css/app.css",
    "d01fb14f2a5db581361b4785abac31bde3855ede": "./mix-manifest.json"
  },
  "strategy": "changed",
  "responseStrategy": "cache-first",
  "version": "5/1/2017, 4:35:22 PM",
  "name": "webpack-offline",
  "pluginVersion": "4.6.2",
  "relativePaths": true
};

/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {



/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";

      

if (typeof DEBUG === 'undefined') {
  var DEBUG = false;
}

function WebpackServiceWorker(params, helpers) {
  var loaders = helpers.loaders;
  var cacheMaps = helpers.cacheMaps;

  var strategy = params.strategy;
  var responseStrategy = params.responseStrategy;

  var assets = params.assets;
  var loadersMap = params.loaders || {};

  var hashesMap = params.hashesMap;
  var externals = params.externals;

  // Not used yet
  // const alwaysRevalidate = params.alwaysRevalidate;
  // const ignoreSearch = params.ignoreSearch;
  // const preferOnline = params.preferOnline;

  var CACHE_PREFIX = params.name;
  var CACHE_TAG = params.version;
  var CACHE_NAME = CACHE_PREFIX + ':' + CACHE_TAG;

  var STORED_DATA_KEY = '__offline_webpack__data';

  mapAssets();

  var allAssets = [].concat(assets.main, assets.additional, assets.optional);
  var navigateFallbackURL = params.navigateFallbackURL;

  self.addEventListener('install', function (event) {
    console.log('[SW]:', 'Install event');

    var installing = undefined;

    if (strategy === 'changed') {
      installing = cacheChanged('main');
    } else {
      installing = cacheAssets('main');
    }

    event.waitUntil(installing);
  });

  self.addEventListener('activate', function (event) {
    console.log('[SW]:', 'Activate event');

    var activation = cacheAdditional();

    // Delete all assets which name starts with CACHE_PREFIX and
    // is not current cache (CACHE_NAME)
    activation = activation.then(storeCacheData);
    activation = activation.then(deleteObsolete);
    activation = activation.then(function () {
      if (self.clients && self.clients.claim) {
        return self.clients.claim();
      }
    });

    event.waitUntil(activation);
  });

  function cacheAdditional() {
    if (!assets.additional.length) {
      return Promise.resolve();
    }

    if (DEBUG) {
      console.log('[SW]:', 'Caching additional');
    }

    var operation = undefined;

    if (strategy === 'changed') {
      operation = cacheChanged('additional');
    } else {
      operation = cacheAssets('additional');
    }

    // Ignore fail of `additional` cache section
    return operation['catch'](function (e) {
      console.error('[SW]:', 'Cache section `additional` failed to load');
    });
  }

  function cacheAssets(section) {
    var batch = assets[section];

    return caches.open(CACHE_NAME).then(function (cache) {
      return addAllNormalized(cache, batch, {
        bust: params.version,
        request: params.prefetchRequest
      });
    }).then(function () {
      logGroup('Cached assets: ' + section, batch);
    })['catch'](function (e) {
      console.error(e);
      throw e;
    });
  }

  function cacheChanged(section) {
    return getLastCache().then(function (args) {
      if (!args) {
        return cacheAssets(section);
      }

      var lastCache = args[0];
      var lastKeys = args[1];
      var lastData = args[2];

      var lastMap = lastData.hashmap;
      var lastVersion = lastData.version;

      if (!lastData.hashmap || lastVersion === params.version) {
        return cacheAssets(section);
      }

      var lastHashedAssets = Object.keys(lastMap).map(function (hash) {
        return lastMap[hash];
      });

      var lastUrls = lastKeys.map(function (req) {
        var url = new URL(req.url);
        url.search = '';

        return url.toString();
      });

      var sectionAssets = assets[section];
      var moved = [];
      var changed = sectionAssets.filter(function (url) {
        if (lastUrls.indexOf(url) === -1 || lastHashedAssets.indexOf(url) === -1) {
          return true;
        }

        return false;
      });

      Object.keys(hashesMap).forEach(function (hash) {
        var asset = hashesMap[hash];

        // Return if not in sectionAssets or in changed or moved array
        if (sectionAssets.indexOf(asset) === -1 || changed.indexOf(asset) !== -1 || moved.indexOf(asset) !== -1) return;

        var lastAsset = lastMap[hash];

        if (lastAsset && lastUrls.indexOf(lastAsset) !== -1) {
          moved.push([lastAsset, asset]);
        } else {
          changed.push(asset);
        }
      });

      logGroup('Changed assets: ' + section, changed);
      logGroup('Moved assets: ' + section, moved);

      var movedResponses = Promise.all(moved.map(function (pair) {
        return lastCache.match(pair[0]).then(function (response) {
          return [pair[1], response];
        });
      }));

      return caches.open(CACHE_NAME).then(function (cache) {
        var move = movedResponses.then(function (responses) {
          return Promise.all(responses.map(function (pair) {
            return cache.put(pair[0], pair[1]);
          }));
        });

        return Promise.all([move, addAllNormalized(cache, changed, {
          bust: params.version,
          request: params.prefetchRequest
        })]);
      });
    });
  }

  function deleteObsolete() {
    return caches.keys().then(function (keys) {
      var all = keys.map(function (key) {
        if (key.indexOf(CACHE_PREFIX) !== 0 || key.indexOf(CACHE_NAME) === 0) return;

        console.log('[SW]:', 'Delete cache:', key);
        return caches['delete'](key);
      });

      return Promise.all(all);
    });
  }

  function getLastCache() {
    return caches.keys().then(function (keys) {
      var index = keys.length;
      var key = undefined;

      while (index--) {
        key = keys[index];

        if (key.indexOf(CACHE_PREFIX) === 0) {
          break;
        }
      }

      if (!key) return;

      var cache = undefined;

      return caches.open(key).then(function (_cache) {
        cache = _cache;
        return _cache.match(new URL(STORED_DATA_KEY, location).toString());
      }).then(function (response) {
        if (!response) return;

        return Promise.all([cache, cache.keys(), response.json()]);
      });
    });
  }

  function storeCacheData() {
    return caches.open(CACHE_NAME).then(function (cache) {
      var data = new Response(JSON.stringify({
        version: params.version,
        hashmap: hashesMap
      }));

      return cache.put(new URL(STORED_DATA_KEY, location).toString(), data);
    });
  }

  self.addEventListener('fetch', function (event) {
    var requestUrl = event.request.url;
    var url = new URL(requestUrl);
    var urlString = undefined;

    if (externals.indexOf(requestUrl) !== -1) {
      urlString = requestUrl;
    } else {
      url.search = '';
      urlString = url.toString();
    }

    // Handle only GET requests
    var isGET = event.request.method === 'GET';
    var assetMatches = allAssets.indexOf(urlString) !== -1;
    var cacheUrl = urlString;

    if (!assetMatches) {
      var cacheRewrite = matchCacheMap(event.request);

      if (cacheRewrite) {
        cacheUrl = cacheRewrite;
        assetMatches = true;
      }
    }

    if (!assetMatches && isGET) {
      // If isn't a cached asset and is a navigation request,
      // fallback to navigateFallbackURL if available
      if (navigateFallbackURL && isNavigateRequest(event.request)) {
        event.respondWith(handleNavigateFallback(fetch(event.request)));

        return;
      }
    }

    if (!assetMatches || !isGET) {
      // Fix for https://twitter.com/wanderview/status/696819243262873600
      if (url.origin !== location.origin && navigator.userAgent.indexOf('Firefox/44.') !== -1) {
        event.respondWith(fetch(event.request));
      }

      return;
    }

    // Logic of caching / fetching is here
    // * urlString -- url to match from the CACHE_NAME
    // * event.request -- original Request to perform fetch() if necessary
    var resource = undefined;

    if (responseStrategy === "network-first") {
      resource = networkFirstResponse(event, urlString, cacheUrl);
    }
    // "cache-first"
    // (responseStrategy has been validated before)
    else {
        resource = cacheFirstResponse(event, urlString, cacheUrl);
      }

    if (navigateFallbackURL && isNavigateRequest(event.request)) {
      resource = handleNavigateFallback(resource);
    }

    event.respondWith(resource);
  });

  self.addEventListener('message', function (e) {
    var data = e.data;
    if (!data) return;

    switch (data.action) {
      case 'skipWaiting':
        {
          if (self.skipWaiting) self.skipWaiting();
        }break;
    }
  });

  function cacheFirstResponse(event, urlString, cacheUrl) {
    return cachesMatch(cacheUrl, CACHE_NAME).then(function (response) {
      if (response) {
        if (DEBUG) {
          console.log('[SW]:', 'URL [' + cacheUrl + '](' + urlString + ') from cache');
        }

        return response;
      }

      // Load and cache known assets
      var fetching = fetch(event.request).then(function (response) {
        if (!response.ok) {
          if (DEBUG) {
            console.log('[SW]:', 'URL [' + urlString + '] wrong response: [' + response.status + '] ' + response.type);
          }

          return response;
        }

        if (DEBUG) {
          console.log('[SW]:', 'URL [' + urlString + '] from network');
        }

        if (cacheUrl === urlString) {
          (function () {
            var responseClone = response.clone();

            caches.open(CACHE_NAME).then(function (cache) {
              return cache.put(urlString, responseClone);
            }).then(function () {
              console.log('[SW]:', 'Cache asset: ' + urlString);
            });
          })();
        }

        return response;
      });

      return fetching;
    });
  }

  function networkFirstResponse(event, urlString, cacheUrl) {
    return fetch(event.request).then(function (response) {
      if (response.ok) {
        if (DEBUG) {
          console.log('[SW]:', 'URL [' + urlString + '] from network');
        }

        return response;
      }

      // throw to reach the code in the catch below
      throw new Error("response is not ok");
    })
    // this needs to be in a catch() and not just in the then() above
    // cause if your network is down, the fetch() will throw
    ['catch'](function () {
      if (DEBUG) {
        console.log('[SW]:', 'URL [' + urlString + '] from cache if possible');
      }

      return cachesMatch(cacheUrl, CACHE_NAME);
    });
  }

  function handleNavigateFallback(fetching) {
    return fetching['catch'](function () {}).then(function (response) {
      if (!response || !response.ok) {
        if (DEBUG) {
          console.log('[SW]:', 'Loading navigation fallback [' + navigateFallbackURL + '] from cache');
        }

        return cachesMatch(navigateFallbackURL, CACHE_NAME);
      }

      return response;
    });
  }

  function mapAssets() {
    Object.keys(assets).forEach(function (key) {
      assets[key] = assets[key].map(function (path) {
        var url = new URL(path, location);

        if (externals.indexOf(path) === -1) {
          url.search = '';
        } else {
          // Remove hash from possible passed externals
          url.hash = '';
        }

        return url.toString();
      });
    });

    Object.keys(loadersMap).forEach(function (key) {
      loadersMap[key] = loadersMap[key].map(function (path) {
        var url = new URL(path, location);

        if (externals.indexOf(path) === -1) {
          url.search = '';
        } else {
          // Remove hash from possible passed externals
          url.hash = '';
        }

        return url.toString();
      });
    });

    hashesMap = Object.keys(hashesMap).reduce(function (result, hash) {
      var url = new URL(hashesMap[hash], location);
      url.search = '';

      result[hash] = url.toString();
      return result;
    }, {});

    externals = externals.map(function (path) {
      var url = new URL(path, location);
      url.hash = '';

      return url.toString();
    });
  }

  function addAllNormalized(cache, requests, options) {
    var allowLoaders = options.allowLoaders !== false;
    var bustValue = options && options.bust;
    var requestInit = options.request || {
      credentials: 'omit',
      mode: 'cors'
    };

    return Promise.all(requests.map(function (request) {
      if (bustValue) {
        request = applyCacheBust(request, bustValue);
      }

      return fetch(request, requestInit);
    })).then(function (responses) {
      if (responses.some(function (response) {
        return !response.ok;
      })) {
        return Promise.reject(new Error('Wrong response status'));
      }

      var extracted = [];
      var addAll = responses.map(function (response, i) {
        if (allowLoaders) {
          extracted.push(extractAssetsWithLoaders(requests[i], response));
        }

        return cache.put(requests[i], response);
      });

      if (extracted.length) {
        (function () {
          var newOptions = copyObject(options);
          newOptions.allowLoaders = false;

          var waitAll = addAll;

          addAll = Promise.all(extracted).then(function (all) {
            var extractedRequests = [].concat.apply([], all);

            if (requests.length) {
              waitAll = waitAll.concat(addAllNormalized(cache, extractedRequests, newOptions));
            }

            return Promise.all(waitAll);
          });
        })();
      } else {
        addAll = Promise.all(addAll);
      }

      return addAll;
    });
  }

  function extractAssetsWithLoaders(request, response) {
    var all = Object.keys(loadersMap).map(function (key) {
      var loader = loadersMap[key];

      if (loader.indexOf(request) !== -1 && loaders[key]) {
        return loaders[key](response.clone());
      }
    }).filter(function (a) {
      return !!a;
    });

    return Promise.all(all).then(function (all) {
      return [].concat.apply([], all);
    });
  }

  function matchCacheMap(request) {
    var urlString = request.url;
    var url = new URL(urlString);

    var requestType = undefined;

    if (request.mode === 'navigate') {
      requestType = 'navigate';
    } else if (url.origin === location.origin) {
      requestType = 'same-origin';
    } else {
      requestType = 'cross-origin';
    }

    for (var i = 0; i < cacheMaps.length; i++) {
      var map = cacheMaps[i];

      if (!map) continue;
      if (map.requestTypes && map.requestTypes.indexOf(requestType) === -1) {
        continue;
      }

      var newString = undefined;

      if (typeof map.match === 'function') {
        newString = map.match(url, request);
      } else {
        newString = urlString.replace(map.match, map.to);
      }

      if (newString && newString !== urlString) {
        return newString;
      }
    }
  }
}

function cachesMatch(request, cacheName) {
  return caches.match(request, {
    cacheName: cacheName
  })
  // Return void if error happened (cache not found)
  ['catch'](function () {});
}

function applyCacheBust(asset, key) {
  var hasQuery = asset.indexOf('?') !== -1;
  return asset + (hasQuery ? '&' : '?') + '__uncache=' + encodeURIComponent(key);
}

function getClientsURLs() {
  if (!self.clients) {
    return Promise.resolve([]);
  }

  return self.clients.matchAll({
    includeUncontrolled: true
  }).then(function (clients) {
    if (!clients.length) return [];

    var result = [];

    clients.forEach(function (client) {
      var url = new URL(client.url);
      url.search = '';
      url.hash = '';
      var urlString = url.toString();

      if (!result.length || result.indexOf(urlString) === -1) {
        result.push(urlString);
      }
    });

    return result;
  });
}

function isNavigateRequest(request) {
  return request.mode === 'navigate' || request.headers.get('Upgrade-Insecure-Requests') || (request.headers.get('Accept') || '').indexOf('text/html') !== -1;
}

function copyObject(original) {
  return Object.keys(original).reduce(function (result, key) {
    result[key] = original[key];
    return result;
  }, {});
}

function logGroup(title, assets) {
  console.groupCollapsed('[SW]:', title);

  assets.forEach(function (asset) {
    console.log('Asset:', asset);
  });

  console.groupEnd();
}
      WebpackServiceWorker(__wpo, {
loaders: {},
cacheMaps: [],
});
      module.exports = __webpack_require__(0)
    

/***/ })
/******/ ]);