const CopyPlugin = require("copy-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const path = require("path");

module.exports = (env) => {
    return {
        entry: {
            main: ['./assets/scripts/index.js'],
        },
        mode: "production",
        module: {
            rules: [
                {
                    test: /\.(js|ts|tsx)$/,
                    enforce: "pre",
                    use: ["source-map-loader"],
                },
                {
                    test: /\.tsx?$/,
                    use: "ts-loader",
                    exclude: /node_modules/,
                },
                {
                    test: /\.css$/i,
                    include: path.resolve(__dirname, "assets/css"),
                    use: [
                        {
                            loader: MiniCssExtractPlugin.loader
                        },
                        "css-loader",
                        "postcss-loader"],
                },
                {
                    test: /\.(gif|jpg|jpeg|png)$/,
                    type: 'asset/resource',
                    generator: { filename: '[name][ext]', },
                },
                {
                    test: /\.(woff|woff2|eot|ttf|otf)$/,
                    type: 'asset/resource',
                    generator: { filename: 'fonts/[name][ext]' },
                }
            ],
        },
        resolve: {
            extensions: [".tsx", ".ts", ".js"],
        },
        plugins: [
            new MiniCssExtractPlugin({filename: "[name].css"}),
        ],
        output: {
            filename: "[name].js",
            path: path.resolve(__dirname, "dist"),
            clean: true,
        },
    }
};
