export const ADRECORD_GLOBAL = window["ADRECORD_GLOBAL"] || {};
export const INITIAL_APIKEY = ADRECORD_GLOBAL.INITIAL_APIKEY || "";
export const INITIAL_CLEAN_LINKS = ADRECORD_GLOBAL.INITIAL_CLEAN_LINKS || false;
export const CURRENT_PROGRAMS_MARKET = ADRECORD_GLOBAL.CURRENT_PROGRAMS_MARKET || 'se';
export const BASE_URL = ADRECORD_GLOBAL.BASE_URL;
export const PLUGIN_URL = ADRECORD_GLOBAL.PLUGIN_URL;

let IMAGE_DIR = "";
if (process.env.NODE_ENV === "production") {
  IMAGE_DIR = PLUGIN_URL + "/build"; //for images
}
export { IMAGE_DIR };
export const REST_NONCE = ADRECORD_GLOBAL.nonce;

export const ADRECORD_URL = "https://api.v2.adrecord.com";
export const PROGRAM_LIMIT = 5;

export const HELP_API_KEY_LINK = "https://www.adrecord.com/sv/advanced/api";



// const { __, _x, _n, _nx } = window.wp.i18n;
const language_dict = ADRECORD_GLOBAL.language_dict

export const translate = term => {
  return language_dict[term] || "";
};


export const MARKETS = [
  {
    id: 'se',
    name: 'Sweden'
  },
  {
    id: 'no',
    name: 'Norway'
  },
  {
    id: 'dk',
    name: 'Denmark'
  },
  {
    id: 'fi',
    name: 'Finland'
  },
  {
    id: 'global',
    name: 'Global'
  },
]