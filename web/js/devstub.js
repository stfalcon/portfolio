function ga(send, event, Order_project, click, arg) {
    console.log('ga', send, event, Order_project, click, arg)
}
function fbq(track, track_name, userEmail) {
    console.log('fbq', track, track_name, userEmail)
}

function getUserEmail(emailId) {
    return $(emailId).val();
}
