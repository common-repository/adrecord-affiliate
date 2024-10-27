import React from "react";
import ReactDOM from "react-dom";
import "./index.css";
import SettingsScreen from "./SettingsScreen";
import DashboardScreen from "./DashboardScreen";

const settings = document.getElementById("wp-adrecord");
if (settings) {
  ReactDOM.render(<SettingsScreen />, settings);
}
const dashboard = document.getElementById("wp-adrecord-dashboard");
if (dashboard) {
  ReactDOM.render(<DashboardScreen />, dashboard);
}
