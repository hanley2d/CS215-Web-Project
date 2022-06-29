var like = document.getElementsByClassName("like");
var dislike = document.getElementsByClassName("dislike");
// add event listeners to all instances of classes "like" and "dislike"
for (var i = 0; i < like.length; i++) {
    like[i].addEventListener("click", sendLike);
    dislike[i].addEventListener("click", sendLike);
}

// sends and updates likes and dislikes when user clicks one of the arrow buttons
function sendLike(e) {
    var field = e.currentTarget;
    var post_id = e.currentTarget.id.split("_")[1]; 
    var likeOrDislike = e.currentTarget.id.split("_")[0]; 
    if (likeOrDislike == "likeButton") {
        var likedata = post_id;
        var dislikedata = ""; 
    }
    else {
        var likedata = "";
        var dislikedata = post_id;
    }
    // output to console if user clicked like or dislike
    console.log(likeOrDislike);  
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange=function() {
        if (xhr.readyState==4 && xhr.status==200) {
            var result = xhr.responseText;
            var responseObj = JSON.parse(result);
            console.log("response object length: " + responseObj.length);
            console.log(responseObj);

            for (var i = 0; i < responseObj.length; i++) {                
                var post = responseObj[i].post_id;
                var likeCount = document.getElementById("likeCount_" + post);
                var dislikeCount = document.getElementById("dislikeCount_" + post);                
                
                if (likeCount) {                                     
                    likeCount.innerHTML = responseObj[i].likes;                    
                    dislikeCount.innerHTML = responseObj[i].dislikes;                    
                    
                    // if blank
                    if (field == like[i] && field.style.color != "orange") {
                        dislike[i].style.color = "#dbd8d2"; 
                        field.style.color = "orange"; 
                        console.log("hello");          
                    }  
                    // if one selected
                    else if (field == like[i] && field.style.color == "orange") {
                        dislike[i].style.color = "#dbd8d2"; 
                        field.style.color = "#dbd8d2"; 
                        console.log("hello");          
                    }    
                    // if blank            
                    if (field == dislike[i] && field.style.color != "orange") {
                        like[i].style.color = "#dbd8d2";  
                        field.style.color = "orange"; 
                        console.log("hello");         
                    } 
                    // if one selected
                    else if (field == dislike[i] && field.style.color == "orange") {
                        like[i].style.color = "#dbd8d2"; 
                        field.style.color = "#dbd8d2"; 
                        console.log("hello");          
                    }        
                }
            }
        }
    }
    console.log("post_id: " + post_id);
    xhr.open("GET", "likes.php?lid=" + likedata + '&did=' + dislikedata, true);
    xhr.send();
}

// update like and dislike count every 20 seconds
setInterval (updateLikes, 20000); // 20000 = 20 seconds.
function updateLikes() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange=function() {
        if (xhr.readyState==4 && xhr.status==200) {
            var result = xhr.responseText;
            var responseObj = JSON.parse(result);
            console.log("update likes/dislikes");

            for (var i = 0; i < responseObj.length; i++) {
                var post = responseObj[i].post_id;
                var likeCount = document.getElementById("likeCount_" + post);
                var dislikeCount = document.getElementById("dislikeCount_" + post);                
                
                if (likeCount) {                    
                    likeCount.innerHTML = responseObj[i].likes;
                    dislikeCount.innerHTML = responseObj[i].dislikes; 
                }            
            }
        }
    }    
    xhr.open("GET", "update.php", true);
    xhr.send();
}

// check if there are new posts every 90 seconds.
// if there are new posts, insert them at the top and delete posts at the end if total posts exceed 20
setInterval (updatePosts, 90000); // 90000 = 90 seconds.
function updatePosts() {
    var xhr = new XMLHttpRequest();

    var posts = document.getElementsByTagName("div");
    var max = 0;     
    var numPosts = document.getElementsByClassName("post");    
    console.log("number of posts: " + numPosts.length);
    

    // find the most recent post_id
    // will use this to check if there are newer posts
    for (var i = 0; i < posts.length; i++) {
        // id value will be a string so need to use function parseInt to convert to int value
        var post_id = parseInt(posts[i].id);       
        if (max < post_id) {
            max = post_id;
        }
    }
    var min = max;
    // min post_id. use to delete last post if necessary
    for (var i = 0; i < posts.length; i++) {
        // id value will be a string so need to use function parseInt to convert to int value
        var post_id = parseInt(posts[i].id);       
        if (min > post_id) {
            min = post_id;
        }
    }
    
    console.log("most recent post_id is " + max);
    console.log("oldest post_id is " + min);
    
    xhr.onreadystatechange=function() {
        if (xhr.readyState==4 && xhr.status==200) {
            var result = xhr.responseText;
            var responseObj = JSON.parse(result);
            console.log("checking for new posts");            
            
            // add new posts
            // Should have made posts less complicated...            
            for (var i = 0; i < responseObj.length; i++) {
                // parent node            
                var container = document.getElementById("container");
                 // all the tags that need to be inserted for a single post
                var divPost = document.createElement("div"); 
                var divPostHeader = document.createElement("div");
                var spanUsername = document.createElement("span");
                var aUsername = document.createElement("a");
                var spanPostDate = document.createElement("span");
                var imgTag = document.createElement("img");
                var pTag = document.createElement("p"); 
                var divPostFooter = document.createElement("div"); 
                var spanLikeButton = document.createElement("span"); 
                var spanLikeCount = document.createElement("span"); 
                var spanDislikeButton = document.createElement("span"); 
                var spanDislikeCount = document.createElement("span"); 
                var aRepost = document.createElement("a");
                // new posts should be inserted at the top so have to use insertBefore()          
                divPost.className = "post";
                container.insertBefore(divPost, container.childNodes[0]);                
                divPostHeader.className = "post_header";
                divPost.appendChild(divPostHeader);
                divPost.id = responseObj[i].post_id;
                spanUsername.className = "user_name";
                divPostHeader.appendChild(spanUsername);                
                aUsername.href = "user_detail.php?user=" + responseObj[i].username;
                aUsername.innerHTML = responseObj[i].username;
                spanUsername.appendChild(aUsername);
                spanPostDate.className = "post_date";
                divPostHeader.appendChild(spanPostDate);
                spanPostDate.innerHTML = responseObj[i].post_date;
                imgTag.src = responseObj[i].avatar_URL;
                divPostHeader.appendChild(imgTag);
                pTag.innerHTML = responseObj[i].post;
                divPost.appendChild(pTag);
                divPostFooter.class = "post_footer";
                divPost.appendChild(divPostFooter);
                spanLikeButton.id = "likeButton_" + responseObj[i].post_id;
                spanLikeButton.className = "like material-icons";
                spanLikeButton.innerHTML = "arrow_circle_up";     
                // add event listener for new like button
                spanLikeButton.addEventListener("click", sendLike);
                divPostFooter.appendChild(spanLikeButton);
                spanLikeCount.id = "likeCount_" + responseObj[i].post_id;                
                spanLikeCount.innerHTML = responseObj[i].Likes;
                divPostFooter.appendChild(spanLikeCount);                
                spanDislikeButton.id = "dislikeButton_" + responseObj[i].post_id;
                spanDislikeButton.className = "dislike material-icons";
                spanDislikeButton.innerHTML = "arrow_circle_down";                
                // add event listener for new dislike button
                divPostFooter.appendChild(spanDislikeButton);
                spanDislikeButton.addEventListener("click", sendLike);
                spanDislikeCount.id = "dislikeCount_" + responseObj[i].post_id;
                spanDislikeCount.innerHTML = responseObj[i].Dislikes;
                divPostFooter.appendChild(spanDislikeCount);
                aRepost.href = "post_repost.php?pid=" + responseObj[i].post_id;
                aRepost.innerHTML = "repost";
                aRepost.style.cssFloat = "right";
                divPostFooter.appendChild(aRepost); 
            }            
            //if the number of posts exceeds 20, delete post
            while (numPosts.length > 20) {
                var deleteThis = document.getElementById(min);               
                deleteThis.remove();
                min++;
            }
        }
    }    
    xhr.open("GET", "update_posts.php?pid=" + max, true);
    xhr.send();
}