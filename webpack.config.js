/* flow */
const AsyncAwaitPlugin = require("webpack-async-await");

module.exports = {
    entry: "./src/main.jsx",
    output: {
        path: "public",
        filename: "bundle.js"
    },
    plugins: [
        new AsyncAwaitPlugin({})
    ],
    module: {
        rules: [
            {
                test: /(\.js|\.jsx)$/,
                use: "babel-loader",
                exclude: /node_modules/
            }
        ]
    },
    devtool: '#@cheap-module-eval-source-map',
};