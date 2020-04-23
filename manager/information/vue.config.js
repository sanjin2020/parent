module.exports={
    devServer: {
        proxy: {
            '/api': {
                target:'http://www.information.com',
                ws: true,
                changeOrigin:true
            },
            '/uploads':{
                target:'http://www.information.com',
                ws: true,
                changeOrigin:true
            }
        }
    },
    publicPath:"./"
};
