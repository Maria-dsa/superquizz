
console.log(document.cookie);

console.log(document.cookie.test);

 function getCookie(cname) {
     let name = cname + "=";
     let ca = document.cookie.split(';');
     for (let i = 0; i < ca.length; i++) {
         let c = ca[i];
         while (c.charAt(0) == ' ') {
             c = c.substring(1);
         }
         if (c.indexOf(name) == 0) {
             return c.substring(name.length, c.length);
         }
     }
     return "";
 }
//  const json = '{"result":true, "count":42}';
//  const obj = JSON.parse(json);

let test = getCookie("score");
let test2 = JSON.parse(test);
console.log(test2);

