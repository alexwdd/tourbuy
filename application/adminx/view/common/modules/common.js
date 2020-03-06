/**

 @Name：layuiAdmin 公共业务
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License：LPPL
    
 */

layui.define(['form','table','laydate'],function(exports) {
    var $ = layui.$,
        layer = layui.layer,
        laydate = layui.laydate,
        laytpl = layui.laytpl,
        setter = layui.setter,
        view = layui.view,
        form = layui.form,
        table = layui.table,
        admin = layui.admin;

    //公共业务的逻辑处理可以写在此处，切换任何页面都会执行
    $.extend({
        //非空判断
        isEmpty: function(value) {
            if (value === null || value == undefined || value === '') {
                return true;
            }
            return false;
        }
    });

    //自定义表单验证
    form.verify({
        /**
         * 对比两个值相等
         */
        "equals": function(value, item){ //value：表单的值、item：表单的DOM对象
        var equalsId = $(item).attr("equalsId");
        if($.isEmpty(equalsId)){
        return '未配置对比id';
        }
        var value2 = $("#"+equalsId).val();

        if(value!==value2)
        {
        var equalsMsg = $(item).attr("equalsMsg");
        if($.isEmpty(equalsMsg))
        {
            equalsMsg = "值不相等";
        }
        return equalsMsg;
        }
        },
        /**
         * 用户名验证
         */
        "username": [
            /^[a-zA-Z]{1}([a-zA-Z0-9]|[_]){2,19}$/,
            '用户名格式不正确!'
        ],
        /**
         * 最小、最大长度判断
         */
        "length": function(value, item){ //value：表单的值、item：表单的DOM对象
        var minLength = $(item).attr("minLength");//最小长度
        var maxLength = $(item).attr("maxLength");//最大长度
        if(!$.isEmpty(minLength) && '0' !== minLength && parseInt(minLength)>value.length){
            return "输入内容小于最小值:"+minLength;
        }
        if(!$.isEmpty(maxLength) && '0' !== maxLength && value.length>parseInt(maxLength)){
            return "输入内容大于最小值:"+maxLength;
        }
        },

        _mobile: function(value) {
            if(value !='') {
                if (!checkMobile(value)) {
                    return '请输入正确的手机号码';
                }
            }
        },
        __mobile: function(value) {
            if (!checkMobile(value)) {
                return '请输入正确的手机号码';
            }
        },
        _url: function(value) {
            if(value !='') {
                if (!checkUrl(value)) {
                    return '请输入正确URL格式';
                }
            }
        },
        __url: function(value) {
            if (!checkUrl(value)) {
                return '请输入正确URL格式';
            }
        },
        __username: function(value) {
            if (!checkWordLong(value,2,20)) {
                return '请输入用户名2-20个字符';
            }
        },
        _password: function(value) {
            if(value !='') {
                if (!checkWordLong(value,6,12)) {
                    return '请输入密码6-12个字符';
                }
            }
        },
        __password: function(value) {
            if (!checkWordLong(value,6,12)) {
                return '请输入密码6-12个字符';
            }
        },
        __repassword: function(value) {
            if (!checkRepassword(value)) {
                return '两次密码不同';
            }
        }
    });

    //日期控件
    laydate.render({
        elem: '.lay-date' //指定元素
    });

    //渲染普通select    
    $("select").each(function(){
        if($(this).attr("default")){
            $(this).val($(this).attr("default"));                   
        }
    })
    form.render();

    //表单提交
    form.on('submit(lay-common-submit)', function(obj) { 
        var gLoad = layer.load(2);
        var iframe = $(obj.elem).attr('iframe');        
        admin.req({
            url: $(obj.elem).attr('url'),            
            data: obj.field,
            method:'post',
            done: function(res) {                
                //登入成功的提示与跳转
                layer.msg(res.msg, {
                    offset: '15px',
                    icon: 1,
                    time: 1000
                }, function() {
                    if (iframe==1){
                        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引  
                        parent.layer.close(index);
                    }else{
                        if(res.url!='' && res.url!=undefined){
                            if (res.url=='reload') {
                                window.location.reload();
                            }else{
                                window.location.href = res.url;
                            }                                   
                        }
                    }                    
                });
            },
            success:function(res){
                layer.close(gLoad);
            }
        });

        if($("#lay-get-vercode").length>0){
            $("#lay-get-vercode").click();
        }
        return false;
    });

    //将模板解析为数据表格列
    getDatagridCols = function(myTable) {
        var colArr = new Array();
        var colsArr = new Array();
        var formatArr = new Array(); //需要格式化的集合
        var datagrid_cols = $(myTable).next(".fsDatagridCols");
        if (!$.isEmpty(datagrid_cols)) {
            var data = {};
            $.each(datagrid_cols.children(), function(i, n) {
                var _this = $(this);

                var type = _this.attr("type");

                if (!$.isEmpty(type) && type == "br") { //换行
                    colArr.push(colsArr);
                    colsArr = new Array();
                    data = {};
                    return true;
                }

                var toolbar = _this.attr("toolbar");
                var col = {};

                if (!$.isEmpty(_this.attr("align"))) {
                    col["align"] = _this.attr("align");
                }
                if (!$.isEmpty(_this.attr("fixed"))) {
                    col["fixed"] = _this.attr("fixed");
                }
                if (!$.isEmpty(_this.attr("style"))) {
                    col["style"] = _this.attr("style");
                }

                if (!$.isEmpty(_this.attr("colspan"))) {
                    col["colspan"] = _this.attr("colspan");
                }
                if (!$.isEmpty(_this.attr("rowspan"))) {
                    col["rowspan"] = _this.attr("rowspan");
                }

                if ($.isEmpty(toolbar)) { //普通列
                    var field = _this.attr("field");
                    var title = _this.attr("title");
                    var width = _this.attr("width");
                    var sort = _this.attr("sort");
                    var templet = _this.attr("templet");
                    var checkbox = _this.attr("checkbox");

                    if (!$.isEmpty(type)) {
                        col["type"] = type;
                    }

                    if (!$.isEmpty(field)) {
                        col["field"] = field;
                    }
                    if (!$.isEmpty(title)) {
                        col["title"] = title;
                    }
                    if (!$.isEmpty(width)) {
                        col["width"] = width;
                    }
                    if (!$.isEmpty(sort)) {
                        col["sort"] = sort;
                    }
                    if (!$.isEmpty(templet)) {
                        col["templet"] = templet;
                    }
                    if (!$.isEmpty(checkbox)) {
                        col["checkbox"] = checkbox;
                    }


                    if (!$.isEmpty(_this.attr("LAY_CHECKED"))) {
                        col["LAY_CHECKED"] = _this.attr("LAY_CHECKED");
                    }
                    if (!$.isEmpty(_this.attr("edit"))) {
                        col["edit"] = _this.attr("edit");
                    }
                    if (!$.isEmpty(_this.attr("event"))) {
                        col["event"] = _this.attr("event");
                    }

                    /*var dict = _this.attr("dict");
                    var formatType = _this.attr("formatType");
                    if (!$.isEmpty(dict)) {
                        formatArr.push(dict);
                        //自定义模板
                        col["templet"] = "<div>{{ layui.fsUtil.toDict('" + dict + "',d." + field + ") }}</div>";
                    } else if (!$.isEmpty(formatType)) {
                        var dateFormat = "yyyy-MM-dd HH:mm:ss";
                        if (formatType == "date") {
                            dateFormat = "yyyy-MM-dd";
                        }
                        col["templet"] = "<div>{{ layui.fsUtil.toDateString(d." + field + ",'" + dateFormat + "') }}</div>";
                    }*/

                    colsArr.push(col);

                } else { //工具条
                    col["toolbar"] = toolbar;
                    var width = _this.attr("width");
                    if (!$.isEmpty(width)) {
                        col["width"] = width;
                    }
                    var title = _this.attr("title");
                    if (!$.isEmpty(title)) {
                        col["title"] = title;
                    }
                    colsArr.push(col);
                }
            });
            colArr.push(colsArr);
        }
        data["colsArr"] = colArr;
        data["formatArr"] = formatArr;
        return data;
    };

    //通用数据表格加载
    $(".lay-common-table").each(function(index, obj) {
        var cols = getDatagridCols(obj);
        //文章管理
        table.render({
            elem: '#lay-common-table',
            url: $(obj).attr("lay-url"),
            method: $(obj).attr("lay-method"),
            cols: cols.colsArr,
            page: true,
            limit: 20,
            limits: [10, 20, 30 , 50 , 100]
        });
        form.render();
    });

    //表格工具条监听
    table.on('tool(lay-common-table)', function(obj) {
        var data = obj.data;
        var _this = $(this);
        if(obj.event === 'edit') {
            var tableId = _this.attr('data-tableId');
            var url = _this.attr('url');            
            var refresh = 1;
            top.layer.open({
                type: 2,
                title: $(this).attr('topTitle'),
                content: url,
                maxmin: true,
                area: [$(this).attr('topWidth'), $(this).attr('topHeight')],
                btn: ['确定'],
                yes: function(index, layero) {
                    //点击确认触发 iframe 内容中的按钮提交
                    var submit = layero.find('iframe').contents().find("#lay-common-submit");
                    submit.click();
                },
                cancel:function(){
                    refresh = 0;
                },
                end:function(){
                    if(tableId!='' && tableId!=undefined && refresh==1) {
                        table.reload(tableId);
                    }
                }
            });
        };
        if(obj.event === 'view') {
            var url = _this.attr('url');
            top.layer.open({
                type: 2,
                title: $(this).attr('topTitle'),
                content: url,
                maxmin: true,
                area: [$(this).attr('topWidth'), $(this).attr('topHeight')],
            });
        }
    });

    //监听搜索
    form.on('submit(tools-btn-search)', function(data) {
        var tableId = ($(data.form).attr("tableId"));
        var field = data.field;
        //执行重载
        table.reload(tableId, {
            where: field
        });
        return false;
    });

    //监听工具栏
    $('.layui-btn.tools-btn').on('click', function() {
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
    });

    var active = {
        action: function() {
            var tableId = $(this).attr('data-tableId');
            var url = $(this).attr('url');
            var alert = $(this).attr('alert');
            var alertMsg = $(this).attr('alertMsg');
            var checkStatus = table.checkStatus(tableId),
                checkData = checkStatus.data; //得到选中的数据
            if (checkData.length === 0) {
                return layer.msg('请选择数据');
            };
            var ids = [];
            for (var i = 0; i < checkData.length; i++) {
                ids.push(checkData[i].id);
            };

            if(alert==1 && alertMsg!=''){
                top.layer.confirm(alertMsg, function(index) {
                    //执行 Ajax 后重载
                    admin.req({
                        url: url,
                        data: {id:ids.join(",")},
                        method:'post',
                        done: function(res) {
                            //登入成功的提示与跳转
                            top.layer.msg(res.msg, {
                                offset: '15px',
                                icon: 1,
                                time: 1000
                            }, function() {
                                table.reload(tableId);
                            });
                        }
                    });
                });
            }else{
                admin.req({
                    url: url,
                    data: {id:ids.join(",")},
                    method:'post',
                    done: function(res) {
                        //登入成功的提示与跳转
                        top.layer.msg(res.msg, {
                            offset: '15px',
                            icon: 1,
                            time: 1000
                        }, function() {
                            table.reload(tableId);
                        });
                    }
                });
            }            
        },
        selectOpen: function() {            
            var tableId = $(this).attr('data-tableId');
            var url = $(this).attr('url');
            var win = $(this).attr('window');
            var refresh = 1;
            var checkStatus = table.checkStatus(tableId),
                checkData = checkStatus.data; //得到选中的数据
            if (checkData.length === 0) {
                return layer.msg('请选择数据');
            };
            var ids = [];
            for (var i = 0; i < checkData.length; i++) {
                ids.push(checkData[i].id);
            };
            url = url+"?id="+ids.join("-");
            if(win==1){
                window.open(url,'_blank');
            }else{
                top.layer.open({
                    type: 2,
                    title: $(this).attr('topTitle'),
                    content: url,
                    maxmin: true,
                    area: [$(this).attr('topWidth'), $(this).attr('topHeight')],
                    btn: ['确定'],
                    yes: function(index, layero) {
                        //点击确认触发 iframe 内容中的按钮提交
                        var submit = layero.find('iframe').contents().find("#lay-common-submit");
                        submit.click();
                    },
                    cancel:function(){
                        refresh = 0;
                    },
                    end:function(){
                        if(tableId!='' && tableId!=undefined && refresh==1) {
                            table.reload(tableId);
                        }
                    }
                });
            }
        },
        add: function() {
            var tableId = $(this).attr('data-tableId');
            var url = $(this).attr('url'); 
            var refresh = 1;
            top.layer.open({
                type: 2,
                title: $(this).attr('topTitle'),
                content: url,
                maxmin: true,
                area: [$(this).attr('topWidth'), $(this).attr('topHeight')],
                btn: ['确定'],
                yes: function(index, layero) {
                    //点击确认触发 iframe 内容中的按钮提交
                    var submit = layero.find('iframe').contents().find("#lay-common-submit");
                    submit.click();
                },
                cancel:function(){
                    refresh = 0;
                },
                end:function(){
                    if(tableId!='' && tableId!=undefined && refresh==1) {
                        table.reload(tableId);
                    }
                }
            });
        },
        refresh: function() {
            var tableId = $(this).attr('data-tableId');
            table.reload(tableId);
        },
        url: function() { 
            var url = $(this).attr('url');
            window.location.href = url;
        },
        back: function() { 
            window.history.go(-1);
        },
    };

    //退出
    admin.events.logout = function() {
        //执行退出接口
        admin.req({
            url: '/adminx/login/signout',
            type: 'get',
            data: {},
            done: function(res) {
                layer.msg('已退出系统', {
                    icon: 1,
                    time: 1000
                }, function() {
                    location.href = '/adminx'; //后台主页
                });             
            }
        });
    };


    //对外暴露的接口
    exports('common', {});
});