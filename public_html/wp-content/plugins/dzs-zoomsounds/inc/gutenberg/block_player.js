/**
 * Block dependencies
 */


/**
 * Internal block libraries
 */


import './block_player.scss';
import * as configSampleData from './configs/config-samples';
import * as helpers from './js_common/_helpers';
import CustomInspectorControls from './js_dzsap/CustomInspectorControls';

let __ = (arg) => {
  return arg;
};

if (wp.i18n) {
  __ = wp.i18n.__;
}

const {registerBlockType} = wp.blocks;

const {
  RichText,
  InspectorControls,
  MediaUpload
} = wp.editor;

const {
  TextareaControl,
  SelectControl,
} = wp.components;

/**
 * Register block
 */


let optionsForPlayer = [];

let player_inspector_controls = null;
let player_attributes = {
  text_string: {
    type: 'string',
  },
  button: {
    type: 'string',
    default: 'button',
  },
  examples_con_opened: {
    type: 'boolean',
    default: false,
  },
  backgroundimage: {
    type: 'string',
    default: null, // no image by default!
  }
};
window.addEventListener('load', function () {


  try {
    optionsForPlayer = JSON.parse(window.dzsap_settings.player_options);
  } catch (err) {
  }


  optionsForPlayer.forEach((optionForPlayer) => {


    let aux = {};

    aux.type = 'string';
    if ((optionForPlayer.type)) {
      aux.type = optionForPlayer.type;
    }
    if ((optionForPlayer['default'])) {

      aux['default'] = optionForPlayer['default'];
    }

    // -- sanitizing
    if (aux.type === 'text' || aux.type === 'textarea') {
      aux.type = 'string';
    }


    if (aux.type === 'string') {
      player_attributes[optionForPlayer.name] = aux;


    }
  })
});


export default registerBlockType('dzsap/gutenberg-player', {
  // Block Title
  title: 'ZoomSounds ' + __('Player'),
  // Block Description
  description: __('Powerful audio player'),
  // Block Category
  category: 'common',
  // Block Icon
  icon: 'format-audio',
  // Block Keywords
  keywords: [
    __('Audio player'),
    __('Sound'),
    __('Song'),
  ],
  attributes: window.dzsap_gutenberg_player_options_for_js_init,
  // Defining the edit interface
  edit: props => {


    const onChangeTweet = value => {
      props.setAttributes({artistname: value});
    };


    let uploadButtonLabel = __('Upload');

    if (props.attributes.dzsap_meta_item_source || props.attributes.source) {
      uploadButtonLabel = __('Select another upload');
    }

    let uploadSongLabel = __('Upload song');


    player_inspector_controls = (
      <CustomInspectorControls
        arr_options={optionsForPlayer}
        skippedKeys={['dzsap_meta_item_source', 'source', 'item_source']}
        uploadButtonLabel={uploadButtonLabel}
        {...props}
      />
    );


    function dzsap_setShortcodeAttribute(args) {
      props.setAttributes(args);
    }

    const import_sample = (arg) => {


      if (arg && arg.getAttribute('data-the-name')) {


        var theName = arg.getAttribute('data-the-name');
        helpers.postAjax(dzsap_settings.siteurl + '?dzsap_action=dzsap_import_vp_config', 'name=' + theName, () => {

          dzsap_setShortcodeAttribute({source: 'https://zoomthe.me/tests/sound-electric.mp3'});
          dzsap_setShortcodeAttribute({config: theName});
          dzsap_setShortcodeAttribute({artistname: theName});
          dzsap_setShortcodeAttribute({examples_con_opened: !props.attributes.examples_con_opened});


          if (theName === 'sample--boxed-inside') {


            dzsap_setShortcodeAttribute({wrapper_image_type: "zoomsounds-wrapper-bg-bellow"});
            dzsap_setShortcodeAttribute({wrapper_image: "https://zoomthe.me/tests/bg_blur.jpg"});

          }
        });
      }
    };

    const examples_con_opened = props.attributes.examples_con_opened;
    const arr_examples = configSampleData.arr_examples;


    {


    }

    return [
      !!props.isSelected && (
        <InspectorControls key="inspector">
          {player_inspector_controls}
        </InspectorControls>
      ),
      <div className={props.className}>
        <div className={(props.attributes.theme ? 'gt-zoomsounds-main-con-alt' : 'gt-zoomsounds-main-con')}>
          <div className="dzsap-gutenberg-con--player zoomsounds-containers">
            <h6 className="gutenberg-title"><span
              className="dashicons dashicons-format-audio"/> {__('Zoomsounds Player')}</h6>
            <h6 className="gutenberg-title"><span className="gutenberg-title--label">{__('Song Name:')}</span> <RichText
              format="string"
              formattingControls={[]}
              placeholder={__('here')}
              onChange={(val) => props.setAttributes({the_post_title: val})}
              value={props.attributes.the_post_title}
            />
            </h6>
            <div className="react-setting-container">
              <div className="react-setting-container--label">{__('Artist name')}</div>
              <div className="react-setting-container--control">
                <RichText
                  format="string"
                  formattingControls={[]}
                  placeholder={__('Input artist name')}
                  onChange={onChangeTweet}
                  value={props.attributes.artistname}
                />
              </div>
            </div>

            <div className="react-setting-container">
              <div className="react-setting-container--label">{__('Song source')}</div>
              <div className="react-setting-container--control">
                <MediaUpload
                  onSelect={(attachmentObject) => {
                    console.log('attachmentObject - ', attachmentObject);


                    var filename = attachmentObject && attachmentObject.filename ? attachmentObject.filename : '';
                    var attachmentId = attachmentObject.id ? attachmentObject.id : 0;
                    var sourceForDzsap = attachmentObject.url;


                    props.setAttributes({source: sourceForDzsap});
                    props.setAttributes({playerid: attachmentId});
                    props.setAttributes({wpAudioPost: attachmentId});


                    if (filename && filename.length > 5 && (filename.indexOf('.mp3') > filename.length - 5 || filename.indexOf('.wav') > filename.length - 5 || filename.indexOf('.m4a') > filename.length - 5)) {
                      window.dzsap_waveform_autogenerateWithId(attachmentId)
                    }


                  }}
                  allowedTypes={['audio']}
                  value={props.attributes.source}
                  render={({open}) => (
                    <div className="render-song-selector">
                      {props.attributes.source ? (
                        <TextareaControl
                          format="string"
                          rows="1"
                          formattingControls={[]}
                          placeholder={__('Input song name')}
                          onChange={(val) => {
                            props.setAttributes({source: val});
                            props.setAttributes({playerid: ''});
                          }}
                          value={props.attributes.source}
                        />
                      ) : ""}
                      <button className="button-secondary" onClick={open}>{uploadSongLabel}</button>
                    </div>
                  )}
                />
              </div>
            </div>

            {
              props.isSelected && optionsForPlayer && !!(helpers.getObjectByKey(optionsForPlayer, 'name', 'config')) && (
                <div className="react-setting-container">
                  <div className="react-setting-container--label">{__('Player configuration')}</div>
                  <div className="react-setting-container--control">

                    {
                      (<SelectControl
                        options={helpers.getObjectByKey(optionsForPlayer, 'name', 'config').choices}
                        onChange={(value) => {
                          props.setAttributes({config: value});
                        }}
                        value={props.attributes.config}
                      />)
                    }

                  </div>
                  <div className={"sidenote"}>
                    <span>{` - ${__('edit configuration from')}`}</span>
                    &nbsp;
                    <a data-suggested-width={1400} className={'ultibox-item-delegated'} target="_blank"
                       href={`${dzsap_settings.admin_url + 'admin.php?page=dzsap_configs&find_slider_by_slug=' + props.attributes.config}`}>{__('here')}</a>
                  </div>
                </div>
              )
            }


            <div className={examples_con_opened ? "gt-zoomsounds-examples-con opened" : "gt-zoomsounds-examples-con "}>
              <h6 onClick={() => {


                props.setAttributes({examples_con_opened: !props.attributes.examples_con_opened});
              }}>{__('Examples')} <span className={"the-icon"}> &#x025BF;</span></h6>
              <div className={"sidenote"}>{__('Import examples with one click')}</div>
              <div className={"dzs-player-examples-con"}>
                {arr_examples.map((el, key) => {
                  return (
                    <div className={"dzs-player-example"} key={key} onClick={(e) => {
                      import_sample(e.currentTarget)
                    }} data-the-name={el.name}>
                      <img alt={"sample image"} className={"the-img"} src={dzsap_settings.thepath + el.img}/>
                      <h6 className={"the-label"}>{el.label}</h6>
                    </div>
                  )
                })}
              </div>
            </div>

          </div>

          <p>
            <a className="ctt-btn">
              {props.attributes.button}
            </a>
          </p>
        </div>
      </div>
    ];
  },
  // Defining the front-end interface
  save() {
    // Rendering in PHP
    return null;
  },
});
