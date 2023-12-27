/* google translate code start */
function callUrl(value){
    location.href = value;
}
function googleTranslateElementInit() {
    new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'ja,en'}, 'google_translate_element');
}
function setCookie(key, value, expiry) {
    delete_cookie(key);
    var expires = new Date();
    expires.setTime(expires.getTime() + (expiry * 24 * 60 * 60 * 1000));
    document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
    // googleTranslateElementInit();
}
document.onreadystatechange = function(e)
{
    if (document.readyState === 'complete')
    {
        if(currLocale == 'en'){
            setCookie('googtrans', '/ja/en',1);
            googleTranslateElementInit();
        }else{
            setCookie('googtrans', '/en/ja',1);
            googleTranslateElementInit();
        }
    }
};

function delete_cookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

window.onload = function(e)
{
    if( window.localStorage )
    {
        if( !localStorage.getItem('firstLoad') )
        {
            localStorage['firstLoad'] = true;
            window.location.reload();
        }  
        else
        {
            localStorage.removeItem('firstLoad');
        }
    }
};
/* google translate code ends */