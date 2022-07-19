const Path = require('path');
const webpackConfig = require('@syntro-opensource/webpack-config');
const {
  resolves,
  modules,
  plugins,
} = webpackConfig;


const ENV = process.env.NODE_ENV;
const PATHS = {
  // The path where your modules are located
  MODULES: 'node_modules',
  // the root path, where your webpack.config.js is located.
  ROOT: Path.resolve(),
  // the root path to your source files
  SRC: Path.resolve('client/src'),
  // thirdparty folder containing copies of packages which wouldn't be available on NPM
  THIRDPARTY: 'thirdparty',
};

const config = [
  {
    name: 'seo-field',
    entry: {
      main: Path.resolve(__dirname, 'client/src/seo-field/bundle.js')
    },
    output: {
      path: Path.resolve(__dirname, 'client/dist/seo-field'),
      filename: '[name].js',
    },
    devtool: (ENV !== 'production') ? 'source-map' : '',

    resolve: {
      ...resolves(ENV, PATHS),
      alias: {
        ...resolves(ENV, PATHS).alias,
        'Components': Path.resolve(__dirname, 'client/src/seo-field/components/'),
        'Hooks': Path.resolve(__dirname, 'client/src/seo-field/hooks/'),
        'Analysis': Path.resolve(__dirname, 'client/src/seo-field/analysis/'),
      },
      extensions: [
        ...resolves(ENV, PATHS).extensions,
        '.js', '.jsx',
      ],
    },
    module: modules(ENV, PATHS),
    plugins: plugins(ENV, PATHS),
    externals: {
      jquery: 'jQuery',
      'lib/Injector': 'Injector',
    },
  }
];

// do any config manipulation here

module.exports = config;
