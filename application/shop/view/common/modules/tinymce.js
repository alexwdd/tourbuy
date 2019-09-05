layui.define(function(exports){
    var config = {
        selector: "#container",
        height: 400,
        language:'zh_CN',
        plugins: [
            "advlist lists searchreplace link image imagetools media fullscreen preview code"
        ],
        toolbar: "bold italic underline forecolor backcolor alignleft aligncenter alignright alignjustify styleselect fontsizeselect searchreplace link unlink image media fullscreen preview code",
        /*toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
        toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | visualchars visualblocks nonbreaking template pagebreak restoredraft",*/
        menubar: false,

        //image_advtab: true, //开启图片上传的高级选项功能
        image_title: false, // 是否开启图片标题设置的选择，这里设置否
        automatic_uploads: true, //开启点击图片上传时，自动进行远程上传操作
        images_upload_base_path: '', // 图片上传的基本路径
        convert_urls:false,
        image_dimensions:false,
        images_upload_url: '/adminx/upload/image?from=editor', //图片上传的具体地址，该选项一定需要设置，才会出现图片上传选项
        images_reuse_filename: true,


        //toolbar_items_size: 'small',
        style_formats: [
            {
                title: 'Bold text',
                inline: 'b'
            }, {
                title: 'Red text',
                inline: 'span',
                styles: {
                    color: '#ff0000'
                }
            }, {
                title: 'Red header',
                block: 'h1',
                styles: {
                    color: '#ff0000'
                }
            }, {
                title: 'Example 1',
                inline: 'span',
                classes: 'example1'
            }, {
                title: 'Example 2',
                inline: 'span',
                classes: 'example2'
            }, {
                title: 'Table styles'
            }, {
                title: 'Table row 1',
                selector: 'tr',
                classes: 'tablerow1'
            }
        ]
    };
    exports('tinymce', config)
});