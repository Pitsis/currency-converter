import * as React from "react";
import Button from "@mui/material/Button";
import Paper from "@mui/material/Paper";

export default function (props) {
  const handleCreateCurrency = () => {
    fetch("/api/currencies", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        name: "New Currency",
        code: "NC",
        symbol: "$",
      }),
    })
      .then((response) => response.json())
      .then((data) => console.log(data))
      .catch((error) => console.error(error));
  };

  // const handleGetCurrency = () => {
  //   fetch("/api/currencies", {
  //     method: "GET",
  //     headers: {
  //       "Content-Type": "application/json",
  //     },
  //   })
  //     .then((response) => response.json())
  //     .then((data) => console.log(data))
  //     .catch((error) => console.error(error));
  // };

  // Example usage:
  const newCurrency = {
    name: "Euro",
    code: "EUR",
    symbol: "€",
  };

  // createCurrency(newCurrency)
  //   .then((currency) => console.log(currency))
  //   .catch((error) => console.error(error));

  return (
    <Paper elevation={3}>
      <Button
        variant="contained"
        color="primary"
        onClick={handleCreateCurrency}
      >
        Create New Currency
      </Button>
    </Paper>
  );
}
