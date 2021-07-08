"use strict";

import { parallel, series } from "gulp";

import styles from "./gulp/styles";

export const clean = series(styles);

export default clean;
