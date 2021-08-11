import React from 'react';

const {
  TextControl,
  TextareaControl,
  SelectControl,
} = wp.components;

let __ = (arg) => {
  return arg;
};

if (wp.i18n) {
  __ = wp.i18n.__;
}
const {
  PlainText,
  MediaUpload
} = wp.editor;

export default class CustomInspectorControls extends React.Component {
  constructor(props) {
    super(props);
    this.props = props;

  }

  render() {

    const ALLOWED_MEDIA_TYPES = ['audio'];
    let uploadSongLabel = __('Upload song');

    return (
      <div className={"components-panel__body is-opened zoomsounds--components-panel__body "}>
        {
          this.props.arr_options.map((optionForBlock, optionIndex) => {



            if(this.props.skippedKeys.includes(optionForBlock.name)){
              return '';
            }

            const props = this.props;

            var gutenberControlArguments = {
                label: optionForBlock.title,
                value: props.attributes[optionForBlock.name] ? props.attributes[optionForBlock.name] : '',
                instanceId: optionForBlock.name,
                onChange: (value) => {
                  props.setAttributes({[optionForBlock.name]: value});
                }

              }
            ;


            let Sidenote = null;

            if (optionForBlock.sidenote) {
              Sidenote = (
                <div className="sidenote" dangerouslySetInnerHTML={{__html: optionForBlock.sidenote}}/>
              )
            }


            const gutenbergControlAttributes = {
              className: "zoomsounds-inspector-setting type-"
            };
            if (optionForBlock.type === 'text' || optionForBlock.type === 'textarea') {

              let gutenbergControl = (<TextControl
                {...gutenberControlArguments}
              />);

              if(optionForBlock.type === 'textarea'){
                gutenbergControl = (<TextareaControl
                  {...gutenberControlArguments}
                />);
              }

              gutenbergControlAttributes.className += optionForBlock.type;
              return (
                <div key={optionIndex} {...gutenbergControlAttributes}>
                  {gutenbergControl}
                  {Sidenote}
                </div>
              )
                ;
            }
            if (optionForBlock.type === 'select') {

              if (optionForBlock.choices && !(optionForBlock.options)) {
                optionForBlock.options = optionForBlock.choices;
              }








              gutenbergControlAttributes.className += optionForBlock.type;
              return (
                <div key={optionIndex} {...gutenbergControlAttributes}>
                  <SelectControl
                    {...gutenberControlArguments}
                    options={optionForBlock.options}
                  />
                  {Sidenote}
                </div>

              )
                ;
            }


            if (optionForBlock.type === 'attach') {

              if (optionForBlock.upload_type) {


                gutenberControlArguments.allowedTypes = [optionForBlock.upload_type];
              }
              gutenberControlArguments.onChange = null;


              if (props.attributes[optionForBlock.name]) {
                uploadSongLabel = __('Select another upload');
              }

              return (
                <div key={optionIndex} className="zoomsounds-inspector-setting type-attach">
                  <label className="components-base-control__label">{optionForBlock.title}</label>
                  <MediaUpload
                    {...gutenberControlArguments}
                    onSelect={(imageObject) => {
                      console.log('imageObject - ', imageObject);
                      props.setAttributes({[optionForBlock.name]: imageObject.url});
                      console.info(' props - ', props);
                    }}
                    render={({open}) => (
                      <div className="render-song-selector">
                        {props.attributes[optionForBlock.name] ? (
                          <PlainText
                            format="string"
                            formattingControls={[]}
                            placeholder={__('Input song name')}
                            onChange={(val) => props.setAttributes({[optionForBlock.name]: val})}
                            value={props.attributes[optionForBlock.name]}
                          />
                        ) : ""}
                        <button className="button-secondary" onClick={open}>{this.props.uploadButtonLabel}</button>
                      </div>
                    )}
                  />
                </div>
              )
                ;
            }


          })
        }
      </div>
    )
;

  }
}