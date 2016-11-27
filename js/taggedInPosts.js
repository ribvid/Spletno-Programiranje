/**
 * Funkcija prikaže objavo, v kateri je oznacen uporabnik
 * @param {String} postId
 */
function revealPost(postId) {
    var post = document.getElementById(postId);
    if(post.style.display == "none") {
        post.style.display = "block";
    } else {
        post.style.display = "none";
    }
}

/**
 * Koda za zaprtje obvestila (notification)
 *
 * Funkcija renameClasses ustrezno preimenuje razrede obvestil, ko je teh vec
 * @param {String} notification
 * @param {String} oldClass
 * @param {String} newClass
 */
var taggedInLink = document.getElementById("tagged-in-link");
taggedInLink.href = "javascript:void(0)";
var close = document.getElementsByClassName("close-notification");
for(var i = 0; i < close.length; i++) {
    close[i].onclick = function() {
        var notification = this.parentElement;
        notification.style.opacity = "0";
        setTimeout(function() {
            notification.style.display = "none";
            if(notification.className.includes("notification-4")) {
                renameClasses(notification.id, "notification-4", "notification-6");
            } else if(notification.className.includes("notification-6")) {
                renameClasses(notification.id, "notification-6", "notification-12");
            }
        }, 600);
    }
}

function renameClasses(notification, oldClass, newClass) {
    id = notification.replace(/^\D+/g, "").toString().split('');
    switch(oldClass) {
        case "notification-6":
            if(id[1] % 2 == 0) {
                /* Prvi v vrsti */
                var sosed = document.getElementById("notification-id-"+id[0]+(parseInt(id[1])+1));
                document.getElementById(notification).removeAttribute("id");
            } else {
                /* Drugi v vrsti */
                var sosed = document.getElementById("notification-id-"+id[0]+(parseInt(id[1])-1));
                document.getElementById(notification).removeAttribute("id");
            }
            sosed.id = "notification-10";
            sosed.className = "col-12 post notification-12";
            break;
        case "notification-4":
            if(id[1] % 3 == 0) {
                /* Prvi v vrsti */
                var sosed1 = document.getElementById("notification-id-"+id[0]+(parseInt(id[1])+1));
                var sosed2 = document.getElementById("notification-id-"+id[0]+(parseInt(id[1])+2));
                sosed1.id = "notification-id-"+parseInt(id[0]+id[1]);
                sosed2.id = "notification-id-"+(parseInt(id[0]+id[1])+1);
                document.getElementById(notification).removeAttribute("id");
            } else if(id[1] % 3 == 1) {
                /* Drugi v vrsti */
                var sosed1 = document.getElementById("notification-id-"+id[0]+(parseInt(id[1])-1));
                var sosed2 = document.getElementById("notification-id-"+id[0]+(parseInt(id[1])+1));
                sosed2.id = "notification-id-"+parseInt(id[0]+id[1]);
                document.getElementById(notification).removeAttribute("id");
            } else {
                /* Tretji v vrsti */
                var sosed1 = document.getElementById("notification-id-"+id[0]+(parseInt(id[1])-2));
                var sosed2 = document.getElementById("notification-id-"+id[0]+(parseInt(id[1])-1));
                document.getElementById(notification).removeAttribute("id");
            }
            sosed1.className = "col-12 post notification-6";
            sosed2.className = "col-12 post notification-6 right-side";
            break;
        default:
            break;
    }
}