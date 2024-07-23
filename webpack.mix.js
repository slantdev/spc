let mix = require("laravel-mix");
let path = require("path");

mix.setResourceRoot("../");
mix.setPublicPath(path.resolve("./"));

mix.webpackConfig({
  watchOptions: {
    ignored: [
      path.posix.resolve(__dirname, "./node_modules"),
      path.posix.resolve(__dirname, "./assets/css"),
      path.posix.resolve(__dirname, "./assets/js"),
    ],
  },
});

mix.js("resources/js/app.js", "js");

mix.postCss("resources/css/app.css", "assets/css");
mix.postCss("resources/css/editor-style.css", "assets/css");
mix.postCss("resources/css/admin-style.css", "assets/css");
mix.postCss("resources/css/acf-layouts.css", "assets/css", [
  require("postcss-prefix-selector")({
    prefix: "#poststuff .acf-layout",
  }),
]);

mix.browserSync({
  proxy: "http://spotlight.local",
  host: "localhost",
  open: "local",
  port: 3000,
  files: ["**/*"],
  injectChanges: false,
});

if (mix.inProduction()) {
  mix.version();
} else {
  mix.options({ manifest: false });
}
