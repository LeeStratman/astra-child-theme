const attributes = {
  linkTypes: {
    type: "array",
    default: ["", "", "", ""],
  },
  selectedTaxonomies: {
    type: "array",
    default: ["", "", "", ""],
  },
  selectedTerms: {
    type: "array",
    default: ["", "", "", ""],
  },
  text: {
    type: "array",
    default: ["", "", "", ""],
  },
  links: {
    type: "array",
    default: ["", "", "", ""],
  },
  selectedImages: {
    type: "array",
    default: ["", "", "", ""],
  },
  selectedImageFocalPoints: {
    type: "array",
    default: [
      { x: 0.5, y: 0.5 },
      { x: 0.5, y: 0.5 },
      { x: 0.5, y: 0.5 },
      { x: 0.5, y: 0.5 },
    ],
  },
};

export default attributes;
