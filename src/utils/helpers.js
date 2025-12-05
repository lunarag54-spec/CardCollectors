// Funciones utilitarias reutilizables
export const formatDate = (date) => {
  return new Date(date).toLocaleDateString('es-ES');
};

export const validateEmail = (email) => {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
};

export const capitalizeString = (str) => {
  return str.charAt(0).toUpperCase() + str.slice(1);
};
