import React from 'react';

/** @var {array} pageUrls */

export default class Images extends React.Component {
    render() {
        const images = pageUrls.map((url, i) => {
            return (
                <a key={i} id={i} href={"#" + (i + 1)}>
                    <img src={url}/>;
                </a>
            )
        });
        console.log(images);
        return (
            <div>
                {images}
            </div>
        );
    }
}