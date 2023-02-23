import * as React from "react";
import { useEffect } from "react";
import Paper from "@mui/material/Paper";
import AmountComponent from "./AmountComponent";
import Grid from "@mui/material/Grid";
import CurrencyComponent from "./CurrencyComponent";
import ArrowRightAltSharpIcon from "@mui/icons-material/ArrowRightAltSharp";
import SouthIcon from "@mui/icons-material/South";

export default function (props) {
  const [currencyFrom, setCurrencyFrom] = React.useState();
  const [currencyTo, setCurrencyTo] = React.useState();
  const [rate, setRate] = React.useState();
  const [amount, setAmount] = React.useState();
  const [symbol, setSymbol] = React.useState();
  const [convertedResult, setConvertedResult] = React.useState();

  /**
   * Function that sets the @var amount every time the user changes the amount field
   */
  useEffect(() => {
    setConvertedResult(amount * rate);
  }, [amount]);

  return (
    <>
      <h1 className="title">Currency Converter</h1>
      <Paper elevation={3}>
        <Grid container spacing={2} justifyContent="center" alignItems="center">
          <Grid item xs={4} md={4} textAlign="center">
            <CurrencyComponent
              label="From"
              name="from-currency"
              id="from-currency"
              inputLabelId="from-currency-label"
              currencyFrom={currencyFrom}
              setCurrencyFrom={setCurrencyFrom}
              setRate={setRate}
              setSymbol={setSymbol}
              setAmount={setAmount}
              setConvertedResult={setConvertedResult}
            />
          </Grid>
          <Grid item xs={2} md={2} textAlign="center">
            {rate ? (
              <>
                <ArrowRightAltSharpIcon fontSize="large" />
                <br />
              </>
            ) : null}
            {rate}
          </Grid>
          <Grid item xs={4} md={4} textAlign="center">
            <CurrencyComponent
              label="To"
              name="to-currency"
              id="to-currency"
              inputLabelId="to-currency-label"
              currencyTo={currencyTo}
              setCurrencyTo={setCurrencyTo}
              setRate={setRate}
              setSymbol={setSymbol}
              setAmount={setAmount}
              setConvertedResult={setConvertedResult}
            />
          </Grid>
          {rate ? (
            <Grid marginY={1} item xs={12} md={12} textAlign="center">
              <AmountComponent
                amount={amount}
                label="Amount"
                setAmount={setAmount}
              />
            </Grid>
          ) : null}
          {convertedResult ? (
            <>
              <Grid marginTop={1} item xs={12} md={12} textAlign="center">
                <SouthIcon fontSize="large" />
              </Grid>
              <Grid marginBottom={1} item xs={12} md={12} textAlign="center">
                {convertedResult.toLocaleString("el-GR", {
                  minimumFractionDigits: 4,
                  maximumFractionDigits: 4,
                })}
                {symbol}
              </Grid>
            </>
          ) : null}
        </Grid>
      </Paper>
    </>
  );
}
