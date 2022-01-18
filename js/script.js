const messages = [
  'username:Username requires alphabet letters or numeric digits',
  'password:Password requires alphabet letters or numeric digits',
  'name:Names require alphabet letters only',
  'email:Email requires a format like, `email_name@email_type.com`'
];
const isEmpty = (field) => (field === "" ? true : false);
const isValidFields = (id, field) => {
  switch (id) {
    case 0:
      return /^[0-9A-Za-z]+$/.test(field);
    case 1:
      // /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/.test(field);
      return /^[0-9A-Za-z]+$/.test(field);
    case 2:
      return /^[A-Za-z]+$/.test(field);
    case 3:
      return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(field);
    default:
      break;
  }
};
const validateInputFields = (id, field, input) => {
  let valid = false;
  if (isEmpty(field)) {
    showInvalid(input, `${messages[id].split(":")[0]} is required`);
  } else if (!isValidFields(id, field)) {
    showInvalid(input, `${messages[id].split(":")[1]}`);
  } else {
    showValid(input, "success");
    valid = true;
  }
  return valid;
};
const showInvalid = (input, message) => {
  const inputFormText = input.parentElement;
  inputFormText.classList.remove("success");
  inputFormText.classList.add("error");
  const error = inputFormText.querySelector("span");
  error.textContent = message;
};
const showValid = (input, type) => {
  const inputFormText = input.parentElement;
  inputFormText.classList.remove("error");
  inputFormText.classList.add(type);
  const error = inputFormText.querySelector("span");
  error.textContent = "";
};
const debounce = (fn, delay) => {
  let timeout;
  return (...args) => {
  if (timeout) {
    clearTimeout(timeout);
  }
  timeout = setTimeout(() => {
      fn.apply(null, args);
    }, delay);
  };
};