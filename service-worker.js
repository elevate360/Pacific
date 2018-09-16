/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */

importScripts("https://storage.googleapis.com/workbox-cdn/releases/3.3.0/workbox-sw.js");

/**
 * The workboxSW.precacheAndRoute() method efficiently caches and responds to
 * requests for URLs in the manifest.
 * See https://goo.gl/S9QRab
 */
self.__precacheManifest = [
  {
    "url": "404.html",
    "revision": "c0d64565a61dc21f429272bf94e8ea7c"
  },
  {
    "url": "assets/css/3.styles.ee4f6afc.css",
    "revision": "558a7913ade810cfe2fffe9072593182"
  },
  {
    "url": "assets/img/search.83621669.svg",
    "revision": "83621669651b9a3d4bf64d1a670ad856"
  },
  {
    "url": "assets/js/0.6677732f.js",
    "revision": "3bf83b740df55d01992305ff9f65c397"
  },
  {
    "url": "assets/js/1.7b757e66.js",
    "revision": "1386fd4ab6fe08e5309f9e877a24746a"
  },
  {
    "url": "assets/js/2.d996d33c.js",
    "revision": "0207485798921bd11546a3a14b8cae1b"
  },
  {
    "url": "assets/js/app.1c9f775f.js",
    "revision": "5265a62531cf117defc266cf4eeb8fa2"
  },
  {
    "url": "config/index.html",
    "revision": "1365624566b600a81068ca0013ee92f7"
  },
  {
    "url": "index.html",
    "revision": "567b4c0af4351b43ba32865693f19f84"
  },
  {
    "url": "logo.png",
    "revision": "e741d22f8ed60c25ffa80a79608a2fdf"
  },
  {
    "url": "screenshot.jpg",
    "revision": "8bd01f067b18032eec2b833543e7e61f"
  },
  {
    "url": "setup/index.html",
    "revision": "03c485e3e5f02d1bb3ab5722ec82b8d8"
  }
].concat(self.__precacheManifest || []);
workbox.precaching.suppressWarnings();
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});
