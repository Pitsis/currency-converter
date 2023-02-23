import * as React from "react";
import TextField from "@mui/material/TextField";

function AmountComponent(props) {
  const handleAmountChange = (event) => {
    props.setAmount(event.target.value);
  };

  return (
    <TextField
      label={props.label}
      onChange={handleAmountChange}
      type="number"
      inputProps={{
        min: 0,
        max: 999999,
        step: 1,
      }}
      value={props.amount}
    />
  );
}

export default AmountComponent;
