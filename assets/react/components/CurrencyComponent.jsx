import * as React from "react";
import Button from "@mui/material/Button";
import InputLabel from "@mui/material/InputLabel";
import MenuItem from "@mui/material/MenuItem";
import FormControl from "@mui/material/FormControl";
import { useState, useEffect } from "react";
import Select, { SelectChangeEvent } from "@mui/material/Select";
import $ from "jquery";

function CurrencyComponent(props) {
  const [currency, setCurrency] = React.useState();
  const [open, setOpen] = React.useState(false);
  const [currencies, setCurrencies] = React.useState([]);

  useEffect(() => {
    fetch("/api/currencies", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => response.json())
      .then((data) => setCurrencies(data))
      .catch((error) => console.error(error));
  }, []);

  useEffect(() => {
    var sourceCode = $("#from-currency").next().val();
    var targetCode = $("#to-currency").next().val();

    if (sourceCode != "" && targetCode != "") {
      fetch(
        `/api/exchangerate?sourceCode=${sourceCode}&targetCode=${targetCode}`,
        {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => response.json())
        .then((data) => {
          // console.log(data);
          data.success
            ? (props.setRate(data.data.rate), props.setSymbol(data.data.symbol))
            : (console.log(data), props.setAmount(""));
        })
        .catch((error) => console.error(error));
    }

    //
  }, [currency]);

  const handleChange = (event) => {
    setCurrency(event.target.value);
    if (event.target.name == "from-currency") {
      props.setCurrencyFrom(event.target.value);
    } else if (event.target.name == "to-currency") {
      props.setCurrencyTo(event.target.value);
    }
  };

  const handleCurrencyClose = () => {
    setOpen(false);
  };

  const handleCurrencyOpen = () => {
    fetch("/api/currencies", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => response.json())
      .then((data) => setCurrencies(data))
      .then(setOpen(true))
      .catch((error) => console.error(error));
  };

  // const handleCreateCurrency = () => {
  //   fetch("/api/currencies", {
  //     method: "POST",
  //     headers: {
  //       "Content-Type": "application/json",
  //     },
  //     body: JSON.stringify({
  //       name: "Great British Pound", // to be changed
  //       code: "GBP", // to be changed
  //       symbol: "£", // to be changed
  //     }),
  //   })
  //     .then((response) => response.json())
  //     .then((data) => console.log(data))
  //     .catch((error) => console.error(error));
  // };

  return (
    <>
      <FormControl sx={{ m: 1, minWidth: 120 }}>
        <InputLabel id={props.inputLabelId}>{props.label}</InputLabel>
        <Select
          labelId={props.inputLabelId}
          id={props.id}
          name={props.name}
          open={open}
          onClose={handleCurrencyClose}
          onOpen={handleCurrencyOpen}
          value={currency}
          label="Currency"
          onChange={handleChange}
        >
          <MenuItem value="">
            <em>None</em>
          </MenuItem>
          {currencies.map((currency) => (
            <MenuItem key={currency.id} value={currency.code}>
              {currency.code}
            </MenuItem>
          ))}
        </Select>
      </FormControl>
    </>
  );
}

export default CurrencyComponent;
