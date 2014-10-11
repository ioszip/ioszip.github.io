require "formula"

class IoszipGithubIo < Formula
  homepage "https://github.com/ioszip/ioszip.github.io"
  url "https://github.com/shoma2da/ioszip.github.io/archive/1.0.0.zip"
  sha1 "114122a1ce3f71dc5cff6d2c1be27a15b40a5da2"

  skip_clean 'bin'

  def install
    prefix.install 'bin'
    bin.chmod 0755
  end

end
