import React, { Component } from 'react';
const IMAGE_FADE_IN_CLASS = `iron-image--fade-in`;

class IronImage extends Component {

  constructor(props) {
    super(props);

    this.state = {
      imageLoadFinishedClass: ``,
      placeholderStyle: { backgroundImage: `url(${props.placeholder})` },
    };

    this.imageLoadHandler = this.imageLoadHandler.bind(this);
  }

  imageLoadHandler() {
      this.setState({ imageLoadFinishedClass: ``});
    this.setState({ imageLoadFinishedClass: IMAGE_FADE_IN_CLASS });
  }

  render() {
    return (
      <div
        className="iron-image__container"
        style={this.state.placeholderStyle}
      >
        <img
          className={`iron-image ${this.state.imageLoadFinishedClass}`}
          alt={this.props.alt}
          src={this.props.src}
          onLoad={this.imageLoadHandler}
        />
      </div>
    );
  };
}

export default IronImage;
