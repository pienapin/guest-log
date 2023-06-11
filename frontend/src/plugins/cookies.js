import { me } from "../services/auth";

const setCookies = (name, value, { datetime }) => {
  const d = new Date();
  if (datetime) d.setSeconds(d.getSeconds() + datetime);
  const expires = `expires=${d.toUTCString()}`;
  document.cookie = `${name}=${value};${expires};path=/;`;
};

const getCookies = (name) => {
  const cookies = `; ${document.cookie}`;
  const byValue = cookies.split(`; ${name}=`);
  if (byValue.length === 2) return byValue.pop().split(';').shift();
};

const delCookies = (name) => {
  setCookies(name, '', -1);
};

const certCookies = () => {
  const token = getCookies('CERT');
  if (token) {
    return me()
      .then((res) => {
        const user = res
        if (!user) return delCookies('CERT');
        return user;
      });
  }
  delCookies('CERT');
  return null;
};

export { setCookies, getCookies, delCookies, certCookies };