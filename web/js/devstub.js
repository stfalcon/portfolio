function ga(send, event, Order_project, click) {
    console.log('ga', send, event, Order_project, click)
}
function fbq(track, track_name, userEmail) {
    console.log('fbq', track, track_name, userEmail)
}

function getUserEmail(emailId) {
    return $(emailId).val();
}
