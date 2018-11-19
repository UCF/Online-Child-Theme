/* global UCFDegreeSearch, Bloodhound */

(function ($) {

  let degree;

  // Return out of all of this if `UCFDegreeSearch` is not defined.
  if (typeof UCFDegreeSearch === 'undefined') {
    return;
  }

  if ($('.degree-search-typeahead')) {
    let keywords = {
        bachelor: ['bachelor\'s', 'bachelors', 'bs', 'ba', 'major', 'majors'],
        master: ['masters', 'ms', 'ma'],
        doctorate: ['phd']
      },
      currentQuery = [''];

    // Returns a token string if true.
    const keywordReplace = function (q) {
      for (const x in q) {
        const term = q[x].toLowerCase().replace(/\.\ \'/, '');
        for (const y in keywords) {
          if (keywords[y].indexOf(term) > -1) {
            q[x] = y;
          }
        }
      }
      return q;
    };

    const prepare = function (query, settings) {
      const token = Bloodhound.tokenizers.whitespace(query);
      query = keywordReplace(token).join(' ');
      settings.url = settings.url.replace(/%q/, query);
      return settings;
    };

    const queryTokenizer = function (q) {
      let token = Bloodhound.tokenizers.whitespace(q);
      token = keywordReplace(token);
      currentQuery = token;
      return token;
    };

    const scoreSorter = function (a, b) {
      if (a.score < b.score) {
        return 1;
      }
      if (a.score > b.score) {
        return -1;
      }
      return 0;
    };

    const addMeta = function (data) {
      const q = currentQuery.join(' '),
        exactMatch = new RegExp(`\\b${q}\\b`, 'i'),
        partialMatch = new RegExp(q, 'i');

      for (const d in data) {
        let result = data[d],
          score = 0,
          matchString = '',
          titleExactMatch = exactMatch.exec(result.title.rendered) !== null,
          titlePartialMatch = partialMatch.exec(result.title.rendered) !== null;

        score += titleExactMatch ? 50 : 0;
        score += titlePartialMatch && !titleExactMatch ? 10 : 0;

        for (const x in result.program_types) {
          const pt = result.program_types[x],
            ptWholeMatch = exactMatch.exec(pt.name) !== null,
            ptPartialMatch = partialMatch.exec(pt.name) !== null;

          score += ptWholeMatch ? 25 : 0;
          score += ptPartialMatch && !ptWholeMatch ? 10 : 0;

          if (ptWholeMatch || ptPartialMatch) {
            matchString = `(Program Type: ${pt.name})`;
          }
        }

        for (const y in result.career_paths) {
          const cp = result.career_paths[y],
            cpWholeMatch = exactMatch.exec(cp.name) !== null,
            cpPartialMatch = partialMatch.exec(cp.name) !== null;

          if (cpWholeMatch || cpPartialMatch) {
            matchString = `(Career Opportunity: ${cp.name})`;
          }
        }

        result.score = score;
        result.matchString = matchString;
      }

      data.sort(scoreSorter);

      if (data.length == degree.limit) {
        data = data.slice(0, -1);
      }

      return data;
    };

    degree = new UCFDegreeSearch({
      transform: addMeta,
      queryTokenizer,
      prepare
    });
  }

}(jQuery));
