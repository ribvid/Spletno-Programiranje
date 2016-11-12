var xmlhttp = new XMLHttpRequest();
var url = "moreContent.json";
xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var posts = JSON.parse(this.responseText);
        loadMoreContent(posts);
    }
};
xmlhttp.open("GET", url, true);
xmlhttp.send();

function loadMoreContent(posts) {
    var out = "";
    var i;
    for(i = 0; i < posts.length; i++) {
        out += posts[i].content + ' ' + posts[i].date + '<br>';
    }
    document.getElementById("result").innerHTML = out;
}