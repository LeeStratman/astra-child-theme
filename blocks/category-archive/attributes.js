const attributes = {
  selectedTaxonomy: {
    type: "string",
    default: "",
  },
  termData: {
    type: "array",
    default: [{ description: "", image: "" }],
  },
  showEmpty: {
    type: "boolean",
    default: false,
  },
};

export default attributes;
