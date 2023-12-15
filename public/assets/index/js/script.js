/**
 * upload 向服务端上传文件
 * @param file object 文件对象
 * @return object xml对象
 */
const upload=(file)=>{
    const url="/api/files/upload";
//     服务端api地址
    xml=new XMLHttpRequest();
//     实例异步请求对象
    xml.open("POST",url,true);
//     发送请求
    fd=new FormData();
    fd.append("img",file);
    xml.send(fd);
    return xml;
}

/**
 * showLoadBar 展示进度条遮罩
 */
const showLoadBar=function(){
    let dom=document.getElementById("load");
    dom.style.display="flex";
    changeJdt(0)
}
/**
 * showLoadBar 隐藏进度条遮罩
 */
const hideLoadBar=function(){
    let dom=document.getElementById("load");
    dom.style.display="none";
    changeJdt(0);
}
/**
 * changeJdt 修改进度条进度
 * @param w 进度图进度百分比
 */
const changeJdt=function(w){
    w= w<0 ? 0 : w;
    w= w>100 ? 100 : w;
    let jdt=document.querySelector("#jdt").getElementsByClassName("jdt-ft")[0];
    jdt.style.width=w+"%";
    return w;
}
const ajaxFinished=function(xml){
    // 封装ajax完成事件
    if(xml.status===200){
        let data=JSON.parse(xml.responseText);
        if (data.status==="success"){
            //     修改img-container为预览图像
            let dom=document.querySelector("#img-container");
            // 在页面展示直链
            document.querySelector("#urlcode").innerHTML=data.url;
            dom.innerHTML="<img src='"+data.url+"' style='max-width:100%;height:auto' />";
            //   生成html代码
            let htmlstr=`&lt;img src="${data.url}" title="小杜的图床" alt='图床无拉！'&gt;`;
            // 写入DOM
            document.querySelector("#htmlcode").innerHTML=htmlstr;
            // 生成md代码
            let mdstr=`![小杜的个人图床](${data.url})`;
            // 写入DOM
            document.querySelector("#mdcode").innerHTML=mdstr;
            //     显示上传信息
            document.getElementsByClassName('result-box')[0].style.display="block";
        }else{
            alert(data.msg);
            window.history.back();
        }
    }
}
/**
 * dropFile 拖动图片到浏览器的事件函数
 * @param e object 事件对象
 */
const dropFile=(e)=>{
    // 获得文件对象
    let file=e.dataTransfer.files[0];
//     异步上传图片
    let xml=upload(file);
    // 显示进度条遮罩
    showLoadBar();
    // 不知道为什么这里是个不可计算长度的数据，只能做个假的进度条了。
        var s=10;
        window.jsq=setInterval(function(){
        s=changeJdt(s)+5;
        },50)
    // 上传完成事件
    xml.onload=()=>{
        //     隐藏进度条遮罩
        hideLoadBar();
        //     销毁计时器
        clearInterval(window.jsq);
        //     预览图像
        ajaxFinished(xml);

    }
}
/**
 * pasteFile拖动图片到浏览器的事件函数
 * @param e object 事件对象
 */
const pasteFile=(e)=>{
    // 获得文件对象
    let file=e.clipboardData.files[0] || window.clipboardData.files[0];
//     异步上传图片
    let xml=upload(file);
    // 显示进度条遮罩
    showLoadBar();
    // 控制进度条变化
    // console.log(xml.upload)
    // 不知道为什么这里是个不可计算长度的数据，只能做个假的进度条了。
    var s=10;
    window.jsq=setInterval(function(){
        s=changeJdt(s)+5;
    },50)
    // 上传完成事件
    xml.onload=()=>{
        //     隐藏进度条遮罩
        hideLoadBar();
        //     销毁计时器
        clearInterval(window.jsq);
        //     预览图像
        ajaxFinished(xml);

    }
}
const clickFile=()=>{
    // 获得文件对象
    let file=input.files[0];
//     异步上传图片
    let xml=upload(file);
    // 显示进度条遮罩
    showLoadBar();
    // 控制进度条变化
    // console.log(xml.upload)
    // 不知道为什么这里是个不可计算长度的数据，只能做个假的进度条了。
    var s=10;
    window.jsq=setInterval(function(){
        s=changeJdt(s)+5;
    },50)
    // 上传完成事件
    xml.onload=()=>{
        //     隐藏进度条遮罩
        hideLoadBar();
        //     销毁计时器
        clearInterval(window.jsq);
        //     预览图像
        ajaxFinished(xml);
    }
}
let changeCode=function(e){
    //  取消已激活的
    document.querySelector(".code-box-active").classList.remove("code-box-active");
    // 激活当前代码块
    id="code-"+e.id;
    document.querySelector(`#${id}`).classList.add("code-box-active")
}
let changeMain=function(e){
    let single=document.querySelector(".main-single");
    let multi=document.querySelector(".main-multi");
    if(e.id==="single"){
        single.style.display="flex";
        multi.style.display="none";
    }else if(e.id==="multi"){
        multi.style.display="flex";
        single.style.display="none";
    }
}
let btnChange=function(e){
    ele=e.srcElement;
    box=ele.parentNode;
    // 移除已激活
    active=box.querySelector(".change-active")
    if(active && (classes=active.classList) && classes!==null){
        box.querySelector(".change-active").classList.remove("change-active");
    }
    // 激活当前元素
    ele.classList.add("change-active");
}

let addFileBtnClick=function(){
    // 点击添加按钮事件
    let input=document.querySelector("#add-file-input");
    // 选中表单元素
    input.click()
    // 点击表单元素
}
/*
* addImg2Page 将图片添加到页面预览
* @param file object 文件对象
*/
let addImg2Page=function(file){
    // 首先检查无文件的样式是否隐藏，如果没有则隐藏
    let noBox=document.querySelector("#list-no-content");
    if(noBox.style.display!=="none"){
        noBox.style.display="none";
    }
    // 在list中写入Dom

    let item=document.createElement("div");
    // 图片列表项容器
    let avatar=document.createElement("img");
    // 缩略图
    let name=document.createElement("span");
    // 图片名
    let code=document.createElement("code");
    // URI、markdown、html等
    let btnGroup=document.createElement("div");
    // 按钮组
    let md=document.createElement("span");
    md.id="item-btn-md";
    // 切换markdown按钮
    let uri=document.createElement("span");
    uri.id="item-btn-uri";
    // 切换到uri的按钮
    let html=document.createElement("span");
    html.id="item-btn-html";
    // 切换到图片标签的按钮
    let remove=document.createElement("span");
    remove.id="item-btn-remove";
    // 移除图片按钮



    item.classList.add("img-list-item");
    item.setAttribute("data-id",imgList.length-1);
    // 容器

    avatar.classList.add("img-item-avatar");
    avatar.setAttribute("src",window.URL.createObjectURL(file));
    avatar.setAttribute("width","auto");
    avatar.setAttribute("height","60px");
    // 预览图

    name.classList.add("img-item-name");
    name.innerHTML=file["name"];
    // 文件名
    code.classList.add("img-item-code");
    code.innerHTML="未开始上传图片....Blob为"+window.URL.createObjectURL(file);
    code.setAttribute("contenteditable","true");
    // 代码

    icon_uri=document.createElement("img");
    icon_uri.setAttribute("src","/assets/index/img/uri.png");
    uri.appendChild(icon_uri);
    // uri按钮

    icon_md=document.createElement("img");
    icon_md.setAttribute("src","/assets/index/img/md.png");
    md.appendChild(icon_md);
    // md按钮
    
    icon_html=document.createElement("img");
    icon_html.setAttribute("src","/assets/index/img/html.png");
    html.appendChild(icon_html);
    // html按钮

    icon_remove=document.createElement("img");
    icon_remove.setAttribute("src","/assets/index/img/delete.png");
    remove.appendChild(icon_remove);

    btnGroup.classList.add("img-item-btn-group");
    btnGroup.appendChild(uri);
    btnGroup.appendChild(md);
    btnGroup.appendChild(html);
    // 按钮组

    // 组合元素
    item.appendChild(avatar);
    item.appendChild(name);
    item.appendChild(code);
    item.appendChild(btnGroup);
    item.appendChild(remove);
    document.querySelector("#file-list-box").appendChild(item);

    // 移除按钮事件
    remove.addEventListener("click",function(e){
        // 被点击的元素
        let src=e.target;
        
        // // 移除预览区
        // document.querySelector("#file-list-box").removeChild(item);
        // // 移除待传列表
        // imgList.splice(imgList.length-1,1);
        // // 更新后面的索引
        // for(x=imgList.length-1;x<=document.getElementsByClassName("img-list-item").length;x++){
        //     // 将所有后面的元素索引-1
        //     document.querySelector(`div[data-id="${x}"]`).setAttribute("data-id",x-1);
        //     console.log(x,document.getElementsByClassName("img-list-item").length);
        // }
        // console.log(e);
    },false);
    // remove.onclick=function(e,i=imgList.length-1) {
    //     // document.querySelector("#file-list-box").removeChild(item);
    //     // 更新后面的索引
    // //     for(x=imgList.length-1;x<document.getElementsByClassName("img-list-item").length;x++){
    // //         // 将所有后面的元素索引-1
    // //         console.log(x,i);
    // //     }
    // //     imgList.splice(imgList.length-1,1);
    //     // console.log(imgList.length-1,i);
    // }
}
let addFile=(e)=>{
    // 选择图片事件
    // 获得文件对象
    let file=e.files[0];
    // 添加到数组
    imgList.push(file);
    // 在页面中展示
    addImg2Page(file)
}


// 写错了 这里不能用循环和ajax异步会导致序列出错。
// let multiUpload=function(){
//     // 多文件上传
//     // 定义结果数组
//     window.imgResult=new Array();
//     for(i=0;i<window.imgList.length;i++){
//         xml=upload(window.imgList[i]);
//         console.log(i);
//         xml.addEventListener("loadend",function(e,j=i){
//             console.log(e);
//             let status=e.srcElement.status;
//             row=document.querySelector(`div[data-id="${j}"]`);
//             console.log(j);
//             if(status==200){
//                 let res=JSON.parse(e.srcElement.responseText);
                // // 添加到结果数组
                // window.imgResult.push(res);
                // // 写入代码框
                // row.querySelector(".img-item-code").innerHTML=res["url"];
                // // 赋予按钮事件
                // row.querySelector("#item-btn-uri").addEventListener("click",function(){
                //     // 切换uri
                //     row.querySelector(".img-item-code").innerHTML=res["url"]
                // });
                // row.querySelector("#item-btn-md").addEventListener("click",function(){
                //     // 切换markdown
                //     let mdstr=`![小杜的个人图床](${res.url})`;
                //     row.querySelector(".img-item-code").innerHTML=mdstr;
                // });
                // row.querySelector("#item-btn-html").addEventListener("click",function(){
                //     // 切换markdown
                //     let htmlstr=`&lt;img src="${res.url}" title="小杜的图床" alt='图床无拉！'&gt;`;
                //     row.querySelector(".img-item-code").innerHTML=htmlstr;
                // });
//             }else{
//                 // 添加到结果数组
//                 window.imgResult.push("上传失败");
//                 // 写入代码框
//                 row.querySelector(".img-item-code").innerHTML="上传失败";
//             }
//         });
//     }
// }
let fetchUpload=function(){
    row=document.querySelector(`[data-id="${imgIndex}"]`);
    try{
        let fd=new FormData();
        fd.append("img",window.imgList[window.imgIndex]);
        let promise=fetch("/api/files/upload",{
            mode:"cors",
            method:"post",
            body:fd
        });
        let json=promise.then(function(e){
            if(e.ok){
                return e.json();
            }else{
                console.log("error");
                return false;
            }
        });
        json.then((res)=>{
            console.log(imgIndex,row);
            // 添加到结果数组
            window.imgResult.push(res);
            // 写入代码框
            row.querySelector(".img-item-code").innerHTML=res["url"];
            // 赋予按钮事件
            row.querySelector("#item-btn-uri").addEventListener("click",function(){
                // 切换uri
                row.querySelector(".img-item-code").innerHTML=res["url"]
            });
            row.querySelector("#item-btn-md").addEventListener("click",function(){
                // 切换markdown
                let mdstr=`![小杜的个人图床](${res.url})`;
                row.querySelector(".img-item-code").innerHTML=mdstr;
            });
            row.querySelector("#item-btn-html").addEventListener("click",function(){
                // 切换markdown
                let htmlstr=`&lt;img src="${res.url}" title="小杜的图床" alt='图床无拉！'&gt;`;
                row.querySelector(".img-item-code").innerHTML=htmlstr;
            });

            // 递归调用，直到达到imgList的最大索引
            if(window.imgIndex<imgList.length-1){
                window.imgIndex++;
                fetchUpload();
            }else{
                // 上传完成
                // 没有做接着上传的代码，如果继续上传导致前面的图片重新上传或者再次缩印偏差，暂时提示刷新后上传
                document.querySelector(".menu-upload").setAttribute("onclick",function(){
                    alert("请刷新后上传");
                });
            }
        })
    }catch(error){
        //添加到结果数组
        window.imgResult.push("上传失败");
        // 写入代码框
        row.querySelector(".img-item-code").innerHTML="上传失败";
        return false;
    }
}
let multiUpload=function(){
    window.imgIndex=0;
    // 当前索引
    window.imgResult=new Array();
    // 结果数组
    // 移除上传按钮事件防止多次上传
    document.querySelector(".menu-upload").setAttribute("onclick",null);
    fetchUpload();
}


let node=document.querySelector("#img-container");
// 取得图片容器元素
let input=document.querySelector("#click-file");
// 取得输入框元素
// 监听事件
window.onload=()=>{
    // 拖放事件
    document.addEventListener("drop",function(e){
        dropFile(e);
        // 禁用拖放，事件监听在文档对象防止用户没有将图片拖动到元素上
        e.preventDefault();
    });
    // 禁用拖放移动
    document.addEventListener("dragover",function(e){
        e.preventDefault();
    })
    // 监听粘贴事件
    node.addEventListener("paste",function(e){
        pasteFile(e);
    }); 
//     监听点击事件
    input.addEventListener("change",function(e){
        clickFile(e)
    });

    // 以下是批量上传部分
    // 构建批量上传全局变量对象
    window.imgList=new Array();

}

// 为change group添加事件
changeList=document.getElementsByClassName("change-group");
for(i=0,len=changeList.length;i<len;i++){
    list=changeList.item(i).children;
    for(j=0;j<list.length;j++){
        list.item(j).addEventListener("click",btnChange);
    }
}
