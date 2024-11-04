const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

module.exports = {
	...defaultConfig,
	...{
		entry: {
			'cno-plugin': __dirname + `/src/index.js`,
		},
		resolve: {
			...defaultConfig.resolve,
			extensions: [ '.js', '.jsx', '.ts', '.tsx' ],
		},
		output: {
			path: __dirname + `/dist`,
			filename: `[name].js`,
		},
	},
};
