import React, { Component } from "react";
import logo from "./assets/adrecord.svg";
import "./App.css";
import styled from "styled-components";
import Toggle from "react-toggle-component";
import "react-toggle-component/styles.css";

import { convertFormDataToArray, convertFormData } from "./utils/form";
import { debounce } from "./utils/debounce";
import {
  BASE_URL,
  REST_NONCE,
  INITIAL_APIKEY,
  INITIAL_CLEAN_LINKS,
  PLUGIN_URL,
  IMAGE_DIR,
  HELP_API_KEY_LINK,
  translate
} from "./globals";

const adrecord_url = "https://api.v2.adrecord.com";

class SettingsScreen extends Component {
  state = {
    user: null,
    loading: false,
    checked: INITIAL_CLEAN_LINKS
  };
  constructor() {
    super();
    this.apikeyRef = React.createRef();
  }
  componentDidMount() {
    if (INITIAL_APIKEY) {
      this.fetchUser(INITIAL_APIKEY);
    }
  }
  async saveApiKey(api_key) {
    let response = await fetch(BASE_URL + "/set_api_key", {
      method: "post",
      credentials: "include",
      headers: { "X-WP-Nonce": REST_NONCE },
      body: JSON.stringify({
        api_key,
        user_id: this.state.user ? this.state.user.userID : "",
        nonce: REST_NONCE
      })
    });
    let json = await response.json();
    // console.log("response: ", json);
  }
  async setCleanLinks(clean_links_enabled) {
    let response = await fetch(BASE_URL + "/set_clean_links_enabled", {
      method: "post",
      credentials: "include",
      headers: { "X-WP-Nonce": REST_NONCE },
      body: JSON.stringify({
        clean_links_enabled,
        nonce: REST_NONCE
      })
    });
    let json = await response.json();
    console.log("response: ", json);
  }

  async fetchUser(api_key) {
    this.setState({ loading: true });
    try {
      let response = await fetch(`${adrecord_url}/user?apikey=${api_key}`);
      let json = await response.json();

      if (json.userID) {
        //Got a valid user.
        this.setState({
          user: json,
          loading: false
        });
        return true;
      } else {
        this.setState({ user: null });
      }
    } catch (e) {
      this.setState({ loading: false, user: null });
    }
    return false;
  }
  handleAPIKeyChange = debounce(async e => {
    const api_key = this.apikeyRef.current.value;
    let success = await this.fetchUser(api_key);
    if (success) {
      this.saveApiKey(api_key);
    }
  }, 300);

  render() {
    const { user, loading } = this.state;
    let incorrectUserId = false;
    if (
      !loading &&
      !user &&
      (this.apikeyRef.current && this.apikeyRef.current.value.length)
    ) {
      incorrectUserId = true;
    }
    return (
      <div className="App">
        <header className="App-header">
          <img src={`${IMAGE_DIR}${logo}`} className="App-logo" alt="logo" />
        </header>
        <div className="App-grid">
          <div className="main-content bordered">
            {!user ? (
              <h2>{translate("get_started")}</h2>
            ) : (
              <h2>{`${translate("welcome")} ${user.firstName}!`}</h2>
            )}

            <StyledForm
              className="settings-form"
              onSubmit={e => {
                e.preventDefault();
                //Always save on form submit
                const api_key = this.apikeyRef.current.value;
                this.saveApiKey(api_key);
              }}
            >
              <div className="bordered">
                <StyledLabel htmlFor="api_key_id">
                  {translate("api_key")}
                </StyledLabel>
                <StyledInput
                  id="api_key_id"
                  name="api_key"
                  incorrect={incorrectUserId}
                  defaultValue={INITIAL_APIKEY}
                  placeholder="123456"
                  autoComplete="off"
                  ref={this.apikeyRef}
                  onChange={this.handleAPIKeyChange}
                />
                <StyledHelpText>
                  {translate("api_key_help_text")}
                  <a href={HELP_API_KEY_LINK}>
                    {translate("api_key_help_text_here")}
                  </a>
                </StyledHelpText>
              </div>

              <br />
              <div className="bordered">
                <Toggle
                  label={translate("clean_links_enabled_text")}
                  checked={this.state.checked}
                  onToggle={value => {
                    this.setState({ checked: value });
                    this.setCleanLinks(value);
                  }}
                />
                <StyledHelpText>
                  {translate("clean_links_help_text")}
                </StyledHelpText>
              </div>
            </StyledForm>
          </div>
          <div className="sidebar">
            <div className="bordered">
              <h2>{translate("about_adrecord_header")}</h2>
              <p>
                {translate("about_text_1")}
                <br />
                <br />
                {translate("about_text_2")}
              </p>
            </div>
            <div className="bordered">
              <h2>{translate("get_started_header")}</h2>
              <p>
                {translate("get_started_text_1")}
                <a
                  href="https://www.adrecord.com/en/signup"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  {translate("get_started_link_1")}
                </a>
              </p>

              <p>
                {translate("get_started_text_2")}
                <a
                  href="https://www.adrecord.com/en/quick-start"
                  target="_blank"
                  rel="noopener noreferrer"
                >
                  {translate("get_started_link_2")}
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
    );
  }
}
export default SettingsScreen;

const StyledForm = styled.form`
  display: flex;
  flex-direction: column;
  justify-content: center;

  label {
    margin-right: 10px;
    font-size: 18px;
  }
`;
const StyledInput = styled.input`
  padding: 10px 12px;
  border: 1px solid ${props => (props.incorrect ? "red" : "#eee")};
  outline: 0;
  border-radius: 4px;
  /* box-shadow: 0 1px 4px rgba(0,0,0,0.1); */
  font-size: 18px;
`;

const StyledLabel = styled.label`
  /* padding: 10px 12px; */
`;
const StyledHelpText = styled.p`
  color: #777;
`;

// window.jQuery.ajax({
//   url: BASE_URL + "/set_api_key",
//   dataType: "json",
//   method: "POST",
//   data: JSON.stringify({ ...data, nonce }),
//   beforeSend: function(xhr) {
//     xhr.setRequestHeader("X-WP-Nonce", nonce);
//   },
//   success: data => {
//     console.log("response: ", data);
//   }
// });
