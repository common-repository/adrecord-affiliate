import React from "react";
import styled from "styled-components";

export function Program({ program }) {
  return (
    <Container>
      <div>
        <a href={"https://www.adrecord.com/sv/join/" + program.slug} target="_blank" title="Program page on Adrecord" rel="noopener noreferrer">
          <h3>{program.name}</h3>
        </a>
        <span>{program.shortDescription || "__Description__"}</span>
      </div>
      <div>
        <span className="span-right">{program.date || "__Date__"}</span>
        <a href={program.url} target="_blank" rel="noopener noreferrer">
          <img src={program.logo} alt={program.name} />
        </a>
      </div>
    </Container>
  );
}

const Container = styled.div`
  flex: 1 1 auto;
  padding: 15px 18px 5px 18px;
  margin: 5px 10px;
  display: flex;
  &:first-child {
    margin-top: 10px;
  }
  &:last-child {
    margin-bottom: 10px;
  }

  box-shadow: 0px 2px 4px #ddd;

  transition: all 0.2s ease-in-out;
  transition-property: transform, box-shadow;
  &:hover {
    transform: translateY(-2px);
    box-shadow: 0px 4px 4px #ddd;
  }

  background: white;
  h3 {
    margin: 0 4px;
    flex: 1 1 auto;
    color: #4a9d24 !important;
  }
  .span-right {
    text-align: right;
  }

  > div {
    display: flex;
    flex-direction: column;
    &:first-child {
      flex: 1 1 auto;
    }
    a:focus {
        box-shadow: none;
    }
  }
`;

// .img-force-aspect {
//   position: relative;
//   ::before {
//     content: "";
//     display: block;
//     padding-bottom: calc(100% / (16 / 9));
//   }
//   > :first-child {
//     position: absolute;
//     top: 0;
//     left: 0;
//     height: 100%;
//   }
// }
