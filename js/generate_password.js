function generatePassword()
{
  var password = "";
  var abc = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  var length = 8;
  for (var i=0; i<length; i++)
    password += abc[parseInt(Math.random()*abc.length)];
  return password;
}