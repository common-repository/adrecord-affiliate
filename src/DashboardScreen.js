import React, { Component } from "react";
import "./App.css";
import styled from "styled-components";

import {
  INITIAL_APIKEY,
  ADRECORD_URL,
  PROGRAM_LIMIT,
  translate,
  CURRENT_PROGRAMS_MARKET,
  MARKETS,
  REST_NONCE,
  BASE_URL
} from "./globals";
import { Program } from "./components/Program";

class DashboardScreen extends Component {
  constructor() {
    super();
    this.state = {
      programs: null,
      market: CURRENT_PROGRAMS_MARKET,
      loading: false
    };
  }

  componentDidMount() {
    this.fetchPrograms();
  }
  async fetchPrograms() {
    if (!INITIAL_APIKEY) {
      return;
    }
    this.setState({ loading: true });
    try {
      let response = await fetch(
        `${ADRECORD_URL}/programs?apikey=${INITIAL_APIKEY}&limit=${PROGRAM_LIMIT}&sortDate=desc&market=${
          this.state.market
        }`
      );
      let json = await response.json();
      if (json.length) {
        this.setState({
          programs: json,
          loading: false
        });
        return true;
      }
    } catch (e) {
      console.log("error fetching", e);
      this.setState({ loading: false });
    }
    return false;
  }

  async saveProgramsMarket(market) {
    try {
      let response = await fetch(BASE_URL + "/set_current_market", {
        method: "post",
        credentials: "include",
        headers: { "X-WP-Nonce": REST_NONCE },
        body: JSON.stringify({
          current_programs_market: market,
          nonce: REST_NONCE
        })
      });
      //update the state and then refetch programs.
      this.setState({ market }, () => {
        this.fetchPrograms();
      });
    } catch (e) {
      console.error("error: ", e);
    }
  }

  render() {
    const { programs, market } = this.state;
    return (
      <div className="App">
        <ProgramHeaderDiv>
          <h3>{translate("latest_programs")}</h3>
          <select
            value={market}
            onChange={e => {
              this.saveProgramsMarket(e.target.value);
            }}
          >
            {MARKETS.map(p => (
              <option key={p.id} value={p.id}>
                {p.name}
              </option>
            ))}
          </select>
        </ProgramHeaderDiv>
        {programs && (
          <LatestPrograms>
            {programs.map(program => (
              <Program key={program.id} program={program} />
            ))}
          </LatestPrograms>
        )}
      </div>
    );
  }
}
export default DashboardScreen;

const LatestPrograms = styled.div`
  margin-top: 10px;
  display: flex;
  flex-direction: column;
  align-items: stretch;
  width: 100%;

  font-size: 12px;
  background: #eee;
`;
const ProgramHeaderDiv = styled.div`
  display: flex;
  justify-content: space-between;
  align-items: center;
`;
