import * as React from "react";
import InputLabel from "@mui/material/InputLabel";
import MenuItem from "@mui/material/MenuItem";
import FormControl from "@mui/material/FormControl";
import { useEffect } from "react";
import Select from "@mui/material/Select";
import $ from "jquery";

function CurrencyComponent(props) {
  const [currency, setCurrency] = React.useState("");
  const [open, setOpen] = React.useState(false);
  const [currencies, setCurrencies] = React.useState([]);

  /**
   * Calls the API to fetch the currencies data and sets the fetched data to the state variable @var currencies.
   */
  useEffect(() => {
    fetch("/api/currencies", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => response.json())
      .then((data) => setCurrencies(data["hydra:member"]))
      .catch((error) => console.error(error));
  }, []);

  /**
   * If both the @var sourceCode" and @var targetCode" are set, makes an API call to retrieve the exchange rate
   * sets the "rate" and "symbol" states if the API call is successful, it also resets the amount field every time a new currency is selected
   * if a currency is selected that doesn't have a related exchangeRate then it just resets the amount and rate
   */
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
          data.success
            ? (props.setRate(data.data.rate),
              props.setSymbol(data.data.symbol),
              props.setAmount(""))
            : (props.setAmount(""), props.setRate(null));
        })
        .catch((error) => console.error(error));
    }
  }, [currency]);

  /**
   * function passed on by the parent component that sets the Currency From or Currency To depending on which component uses it
   */
  const handleCurrencyChange = (event) => {
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
    setOpen(true);
  };

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
          onChange={handleCurrencyChange}
        >
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
